<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KitTemplate;
use App\Models\Student;
use App\Models\StudentSupplyKit;
use App\Models\StudentSupplyKitItem;
use App\Models\SupplyItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentSupplyKitController extends Controller
{
    /**
     * Display student kit distribution records.
     */
    public function index(Request $request)
    {
        $query = StudentSupplyKit::with([
            'student',
            'kitTemplate',
            'items.supplyItem',
        ])->latest('issue_date')->latest('id');

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->whereHas('student', function ($q) use ($search) {

                $q->where('student_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");

            });
        }

        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $studentSupplyKits = $query
            ->paginate(10)
            ->withQueryString();

        $academicYears = StudentSupplyKit::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $stats = [
            'total' => StudentSupplyKit::count(),

            'issued' => StudentSupplyKit::where(
                'status',
                'issued'
            )->count(),

            'pending' => StudentSupplyKit::where(
                'status',
                'pending'
            )->count(),

            'cancelled' => StudentSupplyKit::where(
                'status',
                'cancelled'
            )->count(),
        ];

        return view(
            'admin.student-supply-kits.index',
            compact(
                'studentSupplyKits',
                'academicYears',
                'stats'
            )
        );
    }


    /**
     * Show student kit distribution form.
     */
    public function create()
    {
        $kitTemplates = KitTemplate::with([
            'items.supplyItem',
        ])
            ->where('status', 'active')
            ->orderBy('class')
            ->orderBy('kit_name')
            ->get();

        return view(
            'admin.student-supply-kits.create',
            compact('kitTemplates')
        );
    }


    /**
     * Store a student kit distribution.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'student_id' => [
                'required',
                'exists:students,id',
            ],

            'kit_template_id' => [
                'required',
                'exists:kit_templates,id',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:50',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:pending,issued,cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.supply_item_id' => [
                'required',
                'exists:supply_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);


        DB::transaction(function () use ($validated) {

            /*
             * Lock the selected kit template so that its
             * composition cannot change during distribution.
             */
            $kitTemplate = KitTemplate::with([
                'items.supplyItem',
            ])
                ->lockForUpdate()
                ->findOrFail(
                    $validated['kit_template_id']
                );


            /*
             * Verify that the submitted items belong to
             * the selected government kit template.
             */
            $templateItems = $kitTemplate->items
                ->keyBy('supply_item_id');


            foreach ($validated['items'] as $item) {

                if (!$templateItems->has(
                    (int) $item['supply_item_id']
                )) {

                    abort(
                        422,
                        'One or more selected items do not belong to the selected government kit.'
                    );
                }
            }


            /*
             * Create the distribution record first.
             */
            $studentSupplyKit = StudentSupplyKit::create([

                'student_id' => $validated['student_id'],

                'kit_template_id' =>
                    $validated['kit_template_id'],

                'academic_year' =>
                    $validated['academic_year'],

                'issue_date' =>
                    $validated['issue_date'],

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks'] ?? null,

                'issued_by' =>
                    auth()->id(),
            ]);


            /*
             * Create distributed items.
             *
             * Stock is deducted only when the distribution
             * status is "issued".
             */
            foreach ($validated['items'] as $item) {

                $supplyItem = SupplyItem::lockForUpdate()
                    ->findOrFail(
                        $item['supply_item_id']
                    );

                $quantity = (int) $item['quantity'];


                /*
                 * Make sure the quantity does not exceed
                 * the quantity defined in the government kit.
                 */
                $templateQuantity =
                    (int) $templateItems[
                        (int) $item['supply_item_id']
                    ]->quantity;


                if ($quantity > $templateQuantity) {

                    abort(
                        422,
                        "Quantity for {$supplyItem->item_name} cannot exceed the government kit quantity of {$templateQuantity}."
                    );
                }


                /*
                 * Deduct stock only for an issued kit.
                 */
                if ($validated['status'] === 'issued') {

                    if (
                        $supplyItem->quantity_in_stock
                        < $quantity
                    ) {

                        abort(
                            422,
                            "Insufficient stock for {$supplyItem->item_name}. Available stock: {$supplyItem->quantity_in_stock}."
                        );
                    }

                    $supplyItem->decrement(
                        'quantity_in_stock',
                        $quantity
                    );
                }


                StudentSupplyKitItem::create([

                    'student_supply_kit_id' =>
                        $studentSupplyKit->id,

                    'supply_item_id' =>
                        $supplyItem->id,

                    'quantity' =>
                        $quantity,

                    'condition' =>
                        $item['condition'] ?? 'New',

                    'remarks' =>
                        $item['remarks'] ?? null,
                ]);
            }
        });


        return redirect()
            ->route('admin.student-supply-kits.index')
            ->with(
                'success',
                'Government student supply kit distributed successfully.'
            );
    }


    /**
     * Display a distribution record.
     */
    public function show(
        StudentSupplyKit $studentSupplyKit
    ) {

        $studentSupplyKit->load([
            'student',
            'kitTemplate',
            'items.supplyItem',
        ]);

        return view(
            'admin.student-supply-kits.show',
            compact('studentSupplyKit')
        );
    }


    /**
     * Show edit distribution form.
     */
    public function edit(
        StudentSupplyKit $studentSupplyKit
    ) {

        $studentSupplyKit->load([
            'student',
            'kitTemplate',
            'items.supplyItem',
        ]);

        $kitTemplates = KitTemplate::with([
            'items.supplyItem',
        ])
            ->where('status', 'active')
            ->orderBy('class')
            ->orderBy('kit_name')
            ->get();

        return view(
            'admin.student-supply-kits.edit',
            compact(
                'studentSupplyKit',
                'kitTemplates'
            )
        );
    }


    /**
     * Update distribution record.
     *
     * Stock is restored for an already-issued distribution
     * before applying the updated quantities.
     */
    public function update(
        Request $request,
        StudentSupplyKit $studentSupplyKit
    ) {

        $validated = $request->validate([

            'student_id' => [
                'required',
                'exists:students,id',
            ],

            'kit_template_id' => [
                'required',
                'exists:kit_templates,id',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:50',
            ],

            'issue_date' => [
                'required',
                'date',
            ],

            'status' => [
                'required',
                'in:pending,issued,cancelled',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.supply_item_id' => [
                'required',
                'exists:supply_items,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.condition' => [
                'nullable',
                'string',
                'max:100',
            ],

            'items.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $studentSupplyKit
        ) {

            $studentSupplyKit->load([
                'items',
            ]);


            /*
             * Restore previously deducted stock if the
             * existing distribution was issued.
             */
            if ($studentSupplyKit->status === 'issued') {

                foreach ($studentSupplyKit->items as $oldItem) {

                    SupplyItem::where(
                        'id',
                        $oldItem->supply_item_id
                    )->increment(
                        'quantity_in_stock',
                        $oldItem->quantity
                    );
                }
            }


            /*
             * Lock and load new template.
             */
            $kitTemplate = KitTemplate::with([
                'items.supplyItem',
            ])
                ->lockForUpdate()
                ->findOrFail(
                    $validated['kit_template_id']
                );


            $templateItems = $kitTemplate->items
                ->keyBy('supply_item_id');


            foreach ($validated['items'] as $item) {

                if (!$templateItems->has(
                    (int) $item['supply_item_id']
                )) {

                    abort(
                        422,
                        'One or more selected items do not belong to the selected government kit.'
                    );
                }
            }


            /*
             * Update main distribution record.
             */
            $studentSupplyKit->update([

                'student_id' =>
                    $validated['student_id'],

                'kit_template_id' =>
                    $validated['kit_template_id'],

                'academic_year' =>
                    $validated['academic_year'],

                'issue_date' =>
                    $validated['issue_date'],

                'status' =>
                    $validated['status'],

                'remarks' =>
                    $validated['remarks'] ?? null,
            ]);


            /*
             * Remove previous distribution items.
             */
            $studentSupplyKit->items()->delete();


            /*
             * Create new distribution items.
             */
            foreach ($validated['items'] as $item) {

                $supplyItem = SupplyItem::lockForUpdate()
                    ->findOrFail(
                        $item['supply_item_id']
                    );

                $quantity = (int) $item['quantity'];

                $templateQuantity =
                    (int) $templateItems[
                        (int) $item['supply_item_id']
                    ]->quantity;


                if ($quantity > $templateQuantity) {

                    abort(
                        422,
                        "Quantity for {$supplyItem->item_name} cannot exceed the government kit quantity of {$templateQuantity}."
                    );
                }


                if ($validated['status'] === 'issued') {

                    if (
                        $supplyItem->quantity_in_stock
                        < $quantity
                    ) {

                        abort(
                            422,
                            "Insufficient stock for {$supplyItem->item_name}. Available stock: {$supplyItem->quantity_in_stock}."
                        );
                    }

                    $supplyItem->decrement(
                        'quantity_in_stock',
                        $quantity
                    );
                }


                StudentSupplyKitItem::create([

                    'student_supply_kit_id' =>
                        $studentSupplyKit->id,

                    'supply_item_id' =>
                        $supplyItem->id,

                    'quantity' =>
                        $quantity,

                    'condition' =>
                        $item['condition'] ?? 'New',

                    'remarks' =>
                        $item['remarks'] ?? null,
                ]);
            }
        });


        return redirect()
            ->route(
                'admin.student-supply-kits.show',
                $studentSupplyKit
            )
            ->with(
                'success',
                'Student kit distribution updated successfully.'
            );
    }


    /**
     * Delete a distribution.
     */
    public function destroy(
        StudentSupplyKit $studentSupplyKit
    ) {

        DB::transaction(function () use (
            $studentSupplyKit
        ) {

            $studentSupplyKit->load('items');


            /*
             * Restore stock when an issued distribution
             * is deleted.
             */
            if ($studentSupplyKit->status === 'issued') {

                foreach ($studentSupplyKit->items as $item) {

                    SupplyItem::where(
                        'id',
                        $item->supply_item_id
                    )->increment(
                        'quantity_in_stock',
                        $item->quantity
                    );
                }
            }


            $studentSupplyKit->items()->delete();

            $studentSupplyKit->delete();
        });


        return redirect()
            ->route('admin.student-supply-kits.index')
            ->with(
                'success',
                'Student kit distribution deleted and stock restored.'
            );
    }


    /**
     * Search students for distribution.
     */
    public function searchStudents(
        Request $request
    ): JsonResponse {

        $search = trim(
            $request->get('search', '')
        );


        if (strlen($search) < 2) {

            return response()->json([]);

        }


        $students = Student::query()
            ->select([
                'id',
                'student_id',
                'first_name',
                'middle_name',
                'last_name',
                'class',
                'section',
            ])
            ->where(function ($query) use ($search) {

                $query->where(
                    'student_id',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'first_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'middle_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'last_name',
                        'like',
                        "%{$search}%"
                    );

            })
            ->orderBy('first_name')
            ->limit(10)
            ->get();


        return response()->json(
            $students
        );
    }


    /**
     * Get items for a government kit template.
     */
    public function templateItems(
        Request $request
    ): JsonResponse {

        $request->validate([
            'kit_template_id' => [
                'required',
                'exists:kit_templates,id',
            ],
        ]);


        $kitTemplate = KitTemplate::with([
            'items.supplyItem',
        ])
            ->findOrFail(
                $request->kit_template_id
            );


        $items = $kitTemplate->items->map(
            function ($item) {

                return [
                    'id' =>
                        $item->id,

                    'supply_item_id' =>
                        $item->supply_item_id,

                    'quantity' =>
                        $item->quantity,

                    'condition' =>
                        'New',

                    'remarks' =>
                        $item->remarks,

                    'supply_item' => [
                        'id' =>
                            $item->supplyItem?->id,

                        'item_code' =>
                            $item->supplyItem?->item_code,

                        'item_name' =>
                            $item->supplyItem?->item_name,

                        'unit' =>
                            $item->supplyItem?->unit,

                        'quantity_in_stock' =>
                            $item->supplyItem?->quantity_in_stock ?? 0,
                    ],
                ];
            }
        )->values();


        return response()->json([
            'kit_template' => [
                'id' =>
                    $kitTemplate->id,

                'kit_name' =>
                    $kitTemplate->kit_name,

                'class' =>
                    $kitTemplate->class,

                'academic_year' =>
                    $kitTemplate->academic_year,
            ],

            'items' =>
                $items,
        ]);
    }


    /**
     * Get available supply items.
     */
    public function supplyItems(): JsonResponse
    {
        $items = SupplyItem::query()
            ->where('status', 'active')
            ->orderBy('item_name')
            ->get([
                'id',
                'item_code',
                'item_name',
                'unit',
                'quantity_in_stock',
            ]);

        return response()->json(
            $items
        );
    }


    /**
     * Print student distribution record.
     */
    public function print(
        StudentSupplyKit $studentSupplyKit
    ) {

        $studentSupplyKit->load([
            'student',
            'kitTemplate',
            'items.supplyItem',
        ]);

        return view(
            'admin.student-supply-kits.print',
            compact('studentSupplyKit')
        );
    }
}
