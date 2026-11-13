<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KitTemplate;
use App\Models\Student;
use App\Models\StudentSupplyKit;
use App\Models\StudentSupplyKitItem;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\SupplyStock;
use App\Models\SupplyStockTransaction;

class StudentSupplyKitController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

public function index(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | Academic Year
    |--------------------------------------------------------------------------
    */

    $academicYear = trim((string) $request->input('academic_year'));

    if ($academicYear === '') {
        $startYear = now()->month >= 4
            ? now()->year
            : now()->year - 1;

        $academicYear = $startYear . '-' . substr((string) ($startYear + 1), -2);
    }

    /*
    |--------------------------------------------------------------------------
    | Support both:
    | 2026-27
    | 2026-2027
    |--------------------------------------------------------------------------
    */

    $academicYear = trim((string) $request->input('academic_year'));

if ($academicYear === '') {
    $academicYear = now()->month >= 4
        ? now()->year . '-' . substr((string) (now()->year + 1), -2)
        : (now()->year - 1) . '-' . substr((string) now()->year, -2);
}

/*
|--------------------------------------------------------------------------
| Support both academic-year formats
|--------------------------------------------------------------------------
| Short format : 2026-27
| Long format  : 2026-2027
*/

$academicYearVariants = [];

if (preg_match('/^(\d{4})-(\d{2})$/', $academicYear, $matches)) {

    $startYear = $matches[1];
    $shortEndYear = $matches[2];

    $fullEndYear = substr($startYear, 0, 2) . $shortEndYear;

    $academicYearVariants[] = $startYear . '-' . $shortEndYear;
    $academicYearVariants[] = $startYear . '-' . $fullEndYear;

} elseif (preg_match('/^(\d{4})-(\d{4})$/', $academicYear, $matches)) {

    $startYear = $matches[1];
    $endYear = $matches[2];

    $academicYearVariants[] = $startYear . '-' . substr($endYear, -2);
    $academicYearVariants[] = $startYear . '-' . $endYear;

} else {

    $academicYearVariants[] = $academicYear;
}

$academicYearVariants = array_values(
    array_unique($academicYearVariants)
);

    /*
    |--------------------------------------------------------------------------
    | Classes
    |--------------------------------------------------------------------------
    */

    $classes = Student::query()
        ->where('status', 'active')
        ->whereNotNull('admission_class')
        ->where('admission_class', '!=', '')
        ->select('admission_class')
        ->distinct()
        ->orderBy('admission_class')
        ->pluck('admission_class')
        ->sort(function ($a, $b) {
    return strnatcasecmp((string) $a, (string) $b);
})
->values();

    /*
    |--------------------------------------------------------------------------
    | Sections
    |--------------------------------------------------------------------------
    */

    $sections = Student::query()
        ->where('status', 'active')
        ->whereNotNull('section')
        ->where('section', '!=', '')
        ->select('section')
        ->distinct()
        ->orderBy('section')
        ->pluck('section');

    /*
    |--------------------------------------------------------------------------
    | Government Schemes
    |--------------------------------------------------------------------------
    */

    $schemes = \App\Models\GovernmentScheme::query()
        ->where('status', 'active')
        ->orderBy('scheme_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Kit Templates
    |--------------------------------------------------------------------------
    */

    $kitTemplates = KitTemplate::query()
        ->with('scheme')
        ->where('status', 'active')
        ->orderBy('kit_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Selected Class
    |--------------------------------------------------------------------------
    */

    $selectedClass = trim(
        (string) $request->input('class')
    );

    /*
    |--------------------------------------------------------------------------
    | Class Cards
    |--------------------------------------------------------------------------
    */

    $classCards = $classes->map(function ($className) use (
        $academicYearVariants
    ) {

        /*
        |----------------------------------------------------------------------
        | Students of this class
        |----------------------------------------------------------------------
        */

        $classStudents = Student::query()
            ->where('status', 'active')
            ->where('admission_class', $className)
            ->whereIn('academic_year', $academicYearVariants)
            ->get([
                'id',
                'student_id',
                'first_name',
                'middle_name',
                'last_name',
                'section',
                'academic_year',
            ]);

        $studentIds = $classStudents->pluck('id');

        /*
        |----------------------------------------------------------------------
        | Latest kit record for each student
        |----------------------------------------------------------------------
        */

        $latestKits = StudentSupplyKit::query()
            ->whereIn('student_id', $studentIds)
            ->whereIn('academic_year', $academicYearVariants)
            ->orderByDesc('id')
            ->get([
                'id',
                'student_id',
                'kit_template_id',
                'academic_year',
                'distribution_date',
                'status',
            ])
            ->unique('student_id')
            ->values();

        $issued = $latestKits
            ->where('status', 'issued')
            ->count();

        $pending = $latestKits
            ->where('status', 'pending')
            ->count();

        $cancelled = $latestKits
            ->where('status', 'cancelled')
            ->count();

        $totalStudents = $classStudents->count();

        /*
        |----------------------------------------------------------------------
        | Students with no kit record
        |----------------------------------------------------------------------
        */

        $notAssigned = max(
            0,
            $totalStudents - $latestKits->count()
        );

        /*
        |----------------------------------------------------------------------
        | Card object
        |----------------------------------------------------------------------
        */

        return (object) [
            'class_name'   => $className,
            'total_students' => $totalStudents,
            'issued'        => $issued,
            'pending'       => $pending,
            'cancelled'     => $cancelled,
            'not_assigned'  => $notAssigned,
        ];
    });

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    $students = collect();

    /*
    |--------------------------------------------------------------------------
    | Student Summary
    |--------------------------------------------------------------------------
    */

    $studentSummary = [
        'total'        => 0,
        'issued'       => 0,
        'pending'      => 0,
        'cancelled'    => 0,
        'not_assigned' => 0,
    ];

    /*
    |--------------------------------------------------------------------------
    | If a class is selected
    |--------------------------------------------------------------------------
    */

    if ($selectedClass !== '') {

        /*
        |----------------------------------------------------------------------
        | Get students
        |----------------------------------------------------------------------
        */

        $students = Student::query()
            ->where('status', 'active')
            ->where('admission_class', $selectedClass)
            ->whereIn('academic_year', $academicYearVariants)
            ->orderBy('first_name')
            ->orderBy('middle_name')
            ->orderBy('last_name')
            ->get();

        /*
        |----------------------------------------------------------------------
        | Student IDs
        |----------------------------------------------------------------------
        */

        $studentIds = $students->pluck('id');

        /*
        |----------------------------------------------------------------------
        | Get latest supply kit for every student
        |----------------------------------------------------------------------
        */

        $latestKits = StudentSupplyKit::query()
            ->with([
                'kitTemplate.scheme',
            ])
            ->whereIn('student_id', $studentIds)
            ->whereIn('academic_year', $academicYearVariants)
            ->orderByDesc('id')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');

        /*
        |----------------------------------------------------------------------
        | Attach kit record to student
        |
        | IMPORTANT:
        | Blade uses:
        | $student->supplyKitRecord
        |----------------------------------------------------------------------
        */

        $students->each(function ($student) use ($latestKits) {

            $student->supplyKitRecord =
                $latestKits->get($student->id);
        });

        /*
        |--------------------------------------------------------------------------
        | Student Summary BEFORE FILTERS
        |--------------------------------------------------------------------------
        */

        $studentSummary['total'] = $students->count();

        $studentSummary['issued'] = $students
            ->filter(function ($student) {
                return $student->supplyKitRecord
                    && $student->supplyKitRecord->status === 'issued';
            })
            ->count();

        $studentSummary['pending'] = $students
            ->filter(function ($student) {
                return $student->supplyKitRecord
                    && $student->supplyKitRecord->status === 'pending';
            })
            ->count();

        $studentSummary['cancelled'] = $students
            ->filter(function ($student) {
                return $student->supplyKitRecord
                    && $student->supplyKitRecord->status === 'cancelled';
            })
            ->count();

        $studentSummary['not_assigned'] =
            $studentSummary['total']
            - $studentSummary['issued']
            - $studentSummary['pending']
            - $studentSummary['cancelled'];

        /*
        |--------------------------------------------------------------------------
        | Search Student
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = mb_strtolower(
                trim((string) $request->input('search'))
            );

            $students = $students
                ->filter(function ($student) use ($search) {

                    $fullName = trim(
                        implode(' ', array_filter([
                            $student->first_name,
                            $student->middle_name,
                            $student->last_name,
                        ]))
                    );

                    return
                        str_contains(
                            mb_strtolower($fullName),
                            $search
                        )
                        ||
                        str_contains(
                            mb_strtolower(
                                (string) $student->student_id
                            ),
                            $search
                        );
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Section Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('section')) {

            $students = $students
                ->where(
                    'section',
                    $request->input('section')
                )
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $requestedStatus =
                $request->input('status');

            $students = $students
                ->filter(function ($student) use (
                    $requestedStatus
                ) {

                    $kit =
                        $student->supplyKitRecord;

                    if (!$kit) {
                        return $requestedStatus === 'not_assigned';
                    }

                    return $kit->status === $requestedStatus;
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Government Scheme Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('scheme_id')) {

            $schemeId =
                (int) $request->input('scheme_id');

            $students = $students
                ->filter(function ($student) use ($schemeId) {

                    $kit =
                        $student->supplyKitRecord;

                    if (!$kit) {
                        return false;
                    }

                    return (int) (
                        $kit->kitTemplate?->scheme_id ?? 0
                    ) === $schemeId;
                })
                ->values();
        }

        /*
        |--------------------------------------------------------------------------
        | Kit Template Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('kit_template_id')) {

            $kitTemplateId =
                (int) $request->input('kit_template_id');

            $students = $students
                ->filter(function ($student) use (
                    $kitTemplateId
                ) {

                    $kit =
                        $student->supplyKitRecord;

                    if (!$kit) {
                        return false;
                    }

                    return (int) (
                        $kit->kit_template_id ?? 0
                    ) === $kitTemplateId;
                })
                ->values();
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Existing variable kept for Blade compatibility
    |--------------------------------------------------------------------------
    */

    $studentSupplyKits = collect();

    /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

    return view(
        'admin.student-supply-kits.index',
        compact(
            'studentSupplyKits',
            'classCards',
            'students',
            'selectedClass',
            'academicYear',
            'classes',
            'sections',
            'schemes',
            'kitTemplates',
            'studentSummary'
        )
    );
}
    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $kitTemplates = KitTemplate::with([
            'scheme',
            'items.supplyItem',
        ])
            ->where('status', 'active')
            ->orderBy('academic_year')
            ->orderBy('kit_name')
            ->get();

        $supplyItems = SupplyItem::where('status', 'active')
            ->orderBy('item_name')
            ->get();

        return view(
            'admin.student-supply-kits.create',
            compact(
                'kitTemplates',
                'supplyItems'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
{
    $validated = $request->validate([
        'student_id' => [
            'required',
            'integer',
            'exists:students,id',
        ],

        'kit_template_id' => [
            'required',
            'integer',
            'exists:kit_templates,id',
        ],

        'academic_year' => [
            'required',
            'string',
            'max:20',
        ],

        'distribution_date' => [
            'nullable',
            'date',
        ],

        'status' => [
            'required',
            Rule::in([
                'pending',
                'issued',
                'cancelled',
            ]),
        ],

        'remarks' => [
            'nullable',
            'string',
        ],

        'items' => [
            'nullable',
            'array',
        ],

        'items.*.supply_item_id' => [
            'required',
            'integer',
            'exists:supply_items,id',
        ],

        'items.*.quantity' => [
            'required',
            'integer',
            'min:1',
        ],

        'items.*.issued_quantity' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'items.*.remarks' => [
            'nullable',
            'string',
        ],
    ]);

    DB::transaction(function () use ($validated) {

        /*
        |--------------------------------------------------------------------------
        | CREATE STUDENT SUPPLY KIT
        |--------------------------------------------------------------------------
        */

        $studentSupplyKit = StudentSupplyKit::create([
            'student_id' => $validated['student_id'],
            'kit_template_id' => $validated['kit_template_id'],
            'academic_year' => $validated['academic_year'],
            'distribution_date' =>
                $validated['distribution_date'] ?? null,
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE KIT ITEMS + DEDUCT STOCK
        |--------------------------------------------------------------------------
        */

        foreach ($validated['items'] ?? [] as $item) {

            $issuedQuantity = (int) (
                $item['issued_quantity'] ?? 0
            );

            /*
            |--------------------------------------------------------------------------
            | CREATE STUDENT KIT ITEM
            |--------------------------------------------------------------------------
            */

            StudentSupplyKitItem::create([
                'student_supply_kit_id' =>
                    $studentSupplyKit->id,

                'supply_item_id' =>
                    $item['supply_item_id'],

                'quantity' =>
                    $item['quantity'],

                'issued_quantity' =>
                    $issuedQuantity,

                'remarks' =>
                    $item['remarks'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | NO STOCK DEDUCTION
            |--------------------------------------------------------------------------
            |
            | Pending / cancelled kit with 0 issued quantity does not
            | consume stock.
            |
            */

            if ($issuedQuantity <= 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | LOCK STOCK ROW
            |--------------------------------------------------------------------------
            |
            | lockForUpdate() prevents two users from issuing the same
            | stock at the same time.
            |
            */

            $stock = SupplyStock::where(
                'supply_item_id',
                $item['supply_item_id']
            )
                ->where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->lockForUpdate()
                ->first();

            /*
            |--------------------------------------------------------------------------
            | STOCK RECORD DOES NOT EXIST
            |--------------------------------------------------------------------------
            */

            if (!$stock) {
                throw new \RuntimeException(
                    'Stock record not found for supply item ID '
                    . $item['supply_item_id']
                    . ' for academic year '
                    . $validated['academic_year']
                    . '. Please receive stock first.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | INSUFFICIENT STOCK
            |--------------------------------------------------------------------------
            */

            if ($issuedQuantity > $stock->quantity) {

                $supplyItem = SupplyItem::find(
                    $item['supply_item_id']
                );

                $itemName = $supplyItem
                    ? $supplyItem->item_name
                    : 'Selected item';

                throw new \RuntimeException(
                    $itemName
                    . ': requested issue quantity is '
                    . $issuedQuantity
                    . ', but only '
                    . $stock->quantity
                    . ' is available.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | DEDUCT STOCK
            |--------------------------------------------------------------------------
            */

            $stock->quantity -= $issuedQuantity;
            $stock->save();

            /*
            |--------------------------------------------------------------------------
            | CREATE STOCK ISSUE TRANSACTION
            |--------------------------------------------------------------------------
            */

            SupplyStockTransaction::create([
                'supply_item_id' =>
                    $item['supply_item_id'],

                'academic_year' =>
                    $validated['academic_year'],

                'transaction_type' =>
                    'issue',

                'quantity' =>
                    $issuedQuantity,

                'balance_quantity' =>
                    $stock->quantity,

                'reference_type' =>
                    'student_supply_kit',

                'reference_id' =>
                    $studentSupplyKit->id,

                'remarks' =>
                    $item['remarks'] ?? 'Issued to student supply kit.',

                'created_by' =>
                    auth()->id(),
            ]);
        }
    });

    return redirect()
        ->route('admin.student-supply-kits.index')
        ->with(
            'success',
            'Student supply kit created and stock updated successfully.'
        );
}

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(StudentSupplyKit $studentSupplyKit)
    {
        $studentSupplyKit->load([
            'student',
            'kitTemplate.scheme',
            'kitTemplate.items.supplyItem',
            'items.supplyItem',
        ]);

        return view(
            'admin.student-supply-kits.show',
            compact('studentSupplyKit')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(StudentSupplyKit $studentSupplyKit)
    {
        $studentSupplyKit->load([
            'student',
            'kitTemplate',
            'items.supplyItem',
        ]);

        $kitTemplates = KitTemplate::with([
            'scheme',
            'items.supplyItem',
        ])
            ->where('status', 'active')
            ->orderBy('academic_year')
            ->orderBy('kit_name')
            ->get();

        $supplyItems = SupplyItem::where('status', 'active')
            ->orderBy('item_name')
            ->get();

        return view(
            'admin.student-supply-kits.edit',
            compact(
                'studentSupplyKit',
                'kitTemplates',
                'supplyItems'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
    Request $request,
    StudentSupplyKit $studentSupplyKit
) {
    $validated = $request->validate([
        'student_id' => [
            'required',
            'integer',
            'exists:students,id',
        ],

        'kit_template_id' => [
            'required',
            'integer',
            'exists:kit_templates,id',
        ],

        'academic_year' => [
            'required',
            'string',
            'max:20',
        ],

        'distribution_date' => [
            'nullable',
            'date',
        ],

        'status' => [
            'required',
            Rule::in([
                'pending',
                'issued',
                'cancelled',
            ]),
        ],

        'remarks' => [
            'nullable',
            'string',
        ],

        'items' => [
            'nullable',
            'array',
        ],

        'items.*.supply_item_id' => [
            'required',
            'integer',
            'exists:supply_items,id',
        ],

        'items.*.quantity' => [
            'required',
            'integer',
            'min:1',
        ],

        'items.*.issued_quantity' => [
            'nullable',
            'integer',
            'min:0',
        ],

        'items.*.remarks' => [
            'nullable',
            'string',
        ],
    ]);

    DB::transaction(function () use (
        $validated,
        $studentSupplyKit
    ) {
        /*
        |--------------------------------------------------------------------------
        | 1. Get old issued quantities
        |--------------------------------------------------------------------------
        */

        $oldItems = $studentSupplyKit->items()
            ->get()
            ->groupBy('supply_item_id');

        $oldIssued = [];

        foreach ($oldItems as $supplyItemId => $items) {
            $oldIssued[$supplyItemId] = $items->sum(
                'issued_quantity'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Calculate new issued quantities
        |--------------------------------------------------------------------------
        */

        $newIssued = [];

        foreach ($validated['items'] ?? [] as $item) {
            $supplyItemId = (int) $item['supply_item_id'];

            $issuedQuantity = (int) (
                $item['issued_quantity'] ?? 0
            );

            $newIssued[$supplyItemId] =
                ($newIssued[$supplyItemId] ?? 0)
                + $issuedQuantity;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. Cancelled / Pending means nothing remains issued
        |--------------------------------------------------------------------------
        */

        if ($validated['status'] !== 'issued') {
            $newIssued = [];
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Return previously issued stock first
        |--------------------------------------------------------------------------
        */

        foreach ($oldIssued as $supplyItemId => $oldQuantity) {
            if ($oldQuantity <= 0) {
                continue;
            }

            $newQuantity = $newIssued[$supplyItemId] ?? 0;

            $difference = $newQuantity - $oldQuantity;

            /*
            |--------------------------------------------------------------------------
            | If difference is negative, return stock
            |--------------------------------------------------------------------------
            */

            if ($difference < 0) {
                $returnQuantity = abs($difference);

                $stock = SupplyStock::where(
                    'supply_item_id',
                    $supplyItemId
                )
                    ->where(
                        'academic_year',
                        $validated['academic_year']
                    )
                    ->lockForUpdate()
                    ->first();

                if (!$stock) {
                    throw new \RuntimeException(
                        'Stock record not found while returning stock for supply item ID '
                        . $supplyItemId
                    );
                }

                $stock->quantity += $returnQuantity;
                $stock->save();

                SupplyStockTransaction::create([
                    'supply_item_id' => $supplyItemId,
                    'academic_year' => $validated['academic_year'],
                    'transaction_type' => 'return',
                    'quantity' => $returnQuantity,
                    'balance_quantity' => $stock->quantity,
                    'reference_type' => 'student_supply_kit',
                    'reference_id' => $studentSupplyKit->id,
                    'remarks' => 'Stock returned due to student supply kit update.',
                    'created_by' => auth()->id(),
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Deduct additional stock if issued quantity increased
        |--------------------------------------------------------------------------
        */

        foreach ($newIssued as $supplyItemId => $newQuantity) {
            $oldQuantity = $oldIssued[$supplyItemId] ?? 0;

            $difference = $newQuantity - $oldQuantity;

            if ($difference <= 0) {
                continue;
            }

            $stock = SupplyStock::where(
                'supply_item_id',
                $supplyItemId
            )
                ->where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->lockForUpdate()
                ->first();

            if (!$stock) {
                $supplyItem = SupplyItem::find($supplyItemId);

                $itemName = $supplyItem
                    ? $supplyItem->item_name
                    : 'Selected item';

                throw new \RuntimeException(
                    $itemName
                    . ': stock record not found for academic year '
                    . $validated['academic_year']
                    . '. Please receive stock first.'
                );
            }

            if ($difference > $stock->quantity) {
                $supplyItem = SupplyItem::find($supplyItemId);

                $itemName = $supplyItem
                    ? $supplyItem->item_name
                    : 'Selected item';

                throw new \RuntimeException(
                    $itemName
                    . ': additional issue quantity is '
                    . $difference
                    . ', but only '
                    . $stock->quantity
                    . ' is available.'
                );
            }

            $stock->quantity -= $difference;
            $stock->save();

            SupplyStockTransaction::create([
                'supply_item_id' => $supplyItemId,
                'academic_year' => $validated['academic_year'],
                'transaction_type' => 'issue',
                'quantity' => $difference,
                'balance_quantity' => $stock->quantity,
                'reference_type' => 'student_supply_kit',
                'reference_id' => $studentSupplyKit->id,
                'remarks' => 'Additional stock issued due to student supply kit update.',
                'created_by' => auth()->id(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Update student supply kit
        |--------------------------------------------------------------------------
        */

        $studentSupplyKit->update([
            'student_id' => $validated['student_id'],
            'kit_template_id' => $validated['kit_template_id'],
            'academic_year' => $validated['academic_year'],
            'distribution_date' => $validated['distribution_date'] ?? null,
            'status' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7. Replace kit items
        |--------------------------------------------------------------------------
        */

        $studentSupplyKit->items()->delete();

        foreach ($validated['items'] ?? [] as $item) {
            $issuedQuantity = (int) (
                $item['issued_quantity'] ?? 0
            );

            /*
            |--------------------------------------------------------------------------
            | Pending / Cancelled should not retain issued quantity
            |--------------------------------------------------------------------------
            */

            if ($validated['status'] !== 'issued') {
                $issuedQuantity = 0;
            }

            StudentSupplyKitItem::create([
                'student_supply_kit_id' => $studentSupplyKit->id,
                'supply_item_id' => $item['supply_item_id'],
                'quantity' => $item['quantity'],
                'issued_quantity' => $issuedQuantity,
                'remarks' => $item['remarks'] ?? null,
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
            'Student supply kit updated and stock adjusted successfully.'
        );
}
    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(StudentSupplyKit $studentSupplyKit)
    {
        $studentSupplyKit->delete();

        return redirect()
            ->route('admin.student-supply-kits.index')
            ->with(
                'success',
                'Student supply kit deleted successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT SEARCH
    |--------------------------------------------------------------------------
    */

    public function searchStudents(Request $request)
    {
        $search = trim($request->get('search', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $students = Student::query()
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
            ->where('status', 'active')
            ->orderBy('first_name')
            ->limit(20)
            ->get([
                'id',
                'student_id',
                'first_name',
                'middle_name',
                'last_name',
                'admission_class',
                'section',
                'academic_year',
            ]);

        return response()->json($students);
    }

    /*
    |--------------------------------------------------------------------------
    | TEMPLATE ITEMS
    |--------------------------------------------------------------------------
    */

    
public function templateItems(Request $request)
{
    $validated = $request->validate([
        'kit_template_id' => [
            'required',
            'integer',
            'exists:kit_templates,id',
        ],
    ]);

    $kitTemplate = KitTemplate::with([
        'items.supplyItem',
    ])
        ->where('status', 'active')
        ->findOrFail(
            $validated['kit_template_id']
        );

    return response()->json(
        $kitTemplate->items->map(function ($item) {
            return [
                'supply_item_id' =>
                    $item->supply_item_id,

                'item_name' =>
                    $item->supplyItem?->item_name,

                'item_code' =>
                    $item->supplyItem?->item_code,

                'unit' =>
                    $item->supplyItem?->unit,

                'quantity' =>
                    (int) $item->quantity,

                'remarks' =>
                    $item->remarks,
            ];
        })->values()
    );
}

    /*
    |--------------------------------------------------------------------------
    | SUPPLY ITEMS
    |--------------------------------------------------------------------------
    */

    public function supplyItems(Request $request)
    {
        $query = SupplyItem::query()
            ->where('status', 'active');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'item_name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'item_code',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $items = $query
            ->orderBy('item_name')
            ->limit(50)
            ->get([
                'id',
                'item_name',
                'item_code',
                'unit',
            ]);

        return response()->json($items);
    }
 
    

/*
|--------------------------------------------------------------------------
| PRINT - CONSOLIDATED CLASS-WISE REPORT
|--------------------------------------------------------------------------
*/

public function print(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR
    |--------------------------------------------------------------------------
    */

   

    $academicYear = trim(
        (string) $request->input('academic_year')
    );

    if ($academicYear === '') {

        $startYear = now()->month >= 4
            ? now()->year
            : now()->year - 1;

        $academicYear =
            $startYear . '-' .
            substr((string) ($startYear + 1), -2);
    }

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR VARIANTS
    |--------------------------------------------------------------------------
    |
    | Supports:
    | 2026-27
    | 2026-2027
    |
    */

    $academicYearVariants = [
        $academicYear
    ];

//     dd([
//     'academicYear' => $academicYear,
//     'academicYearVariants' => $academicYearVariants,
//     'variant_count' => count($academicYearVariants),
//     'variant_values' => implode(' | ', $academicYearVariants),
// ]);

    if (
        preg_match(
            '/^(\d{4})-(\d{2})$/',
            $academicYear,
            $matches
        )
    ) {

        $academicYearVariants[] =
            $matches[1] . '-' .
            ((int) $matches[1] + 1);

    } elseif (
        preg_match(
            '/^(\d{4})-(\d{4})$/',
            $academicYear,
            $matches
        )
    ) {

        $academicYearVariants[] =
            $matches[1] . '-' .
            substr($matches[2], -2);
    }

    $academicYearVariants = array_values(
        array_unique($academicYearVariants)
    );

    /*
    |--------------------------------------------------------------------------
    | FILTERS
    |--------------------------------------------------------------------------
    */

    $selectedClass = trim(
        (string) $request->input('class')
    );

    $search = trim(
        (string) $request->input('search')
    );

    $section = trim(
        (string) $request->input('section')
    );

    $status = trim(
        (string) $request->input('status')
    );

    $schemeId = $request->filled('scheme_id')
        ? (int) $request->input('scheme_id')
        : null;

    $kitTemplateId = $request->filled('kit_template_id')
        ? (int) $request->input('kit_template_id')
        : null;

    /*
    |--------------------------------------------------------------------------
    | GET STUDENTS
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | We use the same Student table and fields as index().
    |
    */

    $studentsQuery = Student::query()
        ->where('status', 'active')
        ->whereNotNull('admission_class')
        ->where('admission_class', '!=', '')
        ->whereIn(
            'academic_year',
            $academicYearVariants
        );

    /*
    |--------------------------------------------------------------------------
    | SELECTED CLASS
    |--------------------------------------------------------------------------
    */

    if ($selectedClass !== '') {

        $studentsQuery->where(
            'admission_class',
            $selectedClass
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SECTION
    |--------------------------------------------------------------------------
    */

    if ($section !== '') {

        $studentsQuery->where(
            'section',
            $section
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SEARCH
    |--------------------------------------------------------------------------
    */

    if ($search !== '') {

        $studentsQuery->where(function ($query) use ($search) {

            $query
                ->where(
                    'student_id',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'first_name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'middle_name',
                    'like',
                    '%' . $search . '%'
                )
                ->orWhere(
                    'last_name',
                    'like',
                    '%' . $search . '%'
                );
        });
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    $students = $studentsQuery
        ->orderBy('admission_class')
        ->orderBy('section')
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->orderBy('last_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | GET LATEST KIT FOR EACH STUDENT
    |--------------------------------------------------------------------------
    */

    $studentIds = $students->pluck('id');

    $latestKits = collect();

    if ($studentIds->isNotEmpty()) {

        $latestKits = StudentSupplyKit::query()
            ->with([
                'kitTemplate.scheme',
            ])
            ->whereIn(
                'student_id',
                $studentIds
            )
            ->whereIn(
                'academic_year',
                $academicYearVariants
            )
            ->orderByDesc('id')
            ->get()
            ->unique('student_id')
            ->keyBy('student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ATTACH KIT TO STUDENT
    |--------------------------------------------------------------------------
    */

    $students->each(function ($student) use ($latestKits) {

        $student->supplyKitRecord =
            $latestKits->get($student->id);
    });

    /*
    |--------------------------------------------------------------------------
    | STATUS FILTER
    |--------------------------------------------------------------------------
    */

    if ($status !== '') {

        $students = $students
            ->filter(function ($student) use ($status) {

                $kit = $student->supplyKitRecord;

                if (!$kit) {

                    return $status === 'not_assigned';
                }

                return $kit->status === $status;
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | SCHEME FILTER
    |--------------------------------------------------------------------------
    */

    if ($schemeId !== null) {

        $students = $students
            ->filter(function ($student) use ($schemeId) {

                $kit = $student->supplyKitRecord;

                if (!$kit) {
                    return false;
                }

                return (int) (
                    $kit->kitTemplate?->scheme_id ?? 0
                ) === $schemeId;
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | KIT TEMPLATE FILTER
    |--------------------------------------------------------------------------
    */

    if ($kitTemplateId !== null) {

        $students = $students
            ->filter(function ($student) use (
                $kitTemplateId
            ) {

                $kit = $student->supplyKitRecord;

                if (!$kit) {
                    return false;
                }

                return (int) (
                    $kit->kit_template_id ?? 0
                ) === $kitTemplateId;
            })
            ->values();
    }

    /*
    |--------------------------------------------------------------------------
    | SCHOOL PROFILE
    |--------------------------------------------------------------------------
    */

    $schoolSetting =
        \App\Models\SchoolSetting::first();

    /*
    |--------------------------------------------------------------------------
    | SELECTED SCHEME
    |--------------------------------------------------------------------------
    */

    $selectedScheme = null;

    if ($schemeId !== null) {

        $selectedScheme =
            \App\Models\GovernmentScheme::find(
                $schemeId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SELECTED KIT TEMPLATE
    |--------------------------------------------------------------------------
    */

    $selectedKitTemplate = null;

    if ($kitTemplateId !== null) {

        $selectedKitTemplate =
            KitTemplate::find(
                $kitTemplateId
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

    $summary = [
        'total' => $students->count(),

        'issued' => $students
            ->filter(function ($student) {

                return $student->supplyKitRecord
                    && $student->supplyKitRecord->status === 'issued';
            })
            ->count(),

        'pending' => $students
            ->filter(function ($student) {

                return $student->supplyKitRecord
                    && $student->supplyKitRecord->status === 'pending';
            })
            ->count(),

        'cancelled' => $students
            ->filter(function ($student) {

                return $student->supplyKitRecord
                    && $student->supplyKitRecord->status === 'cancelled';
            })
            ->count(),

        'not_assigned' => $students
            ->filter(function ($student) {

                return !$student->supplyKitRecord;
            })
            ->count(),
    ];

    /*
    |--------------------------------------------------------------------------
    | REPORT MODE
    |--------------------------------------------------------------------------
    */

    $reportMode = $selectedClass === ''
        ? 'all'
        : 'class';

    /*
    |--------------------------------------------------------------------------
    | REPORT TITLE
    |--------------------------------------------------------------------------
    */

    $reportTitle = $selectedClass === ''
        ? 'Student Supply Kit - All Classes Report'
        : 'Student Supply Kit Report - ' . $selectedClass;

    /*
    |--------------------------------------------------------------------------
    | DEBUG DATA COUNT
    |--------------------------------------------------------------------------
    |
    | This is useful because the print Blade receives $students directly.
    |
    */

    return view(
        'admin.student-supply-kits.print',
        compact(
            'students',
            'academicYear',
            'selectedClass',
            'search',
            'section',
            'status',
            'schemeId',
            'kitTemplateId',
            'selectedScheme',
            'selectedKitTemplate',
            'schoolSetting',
            'summary',
            'reportMode',
            'reportTitle'
        )
    );
}

/*                                                                         |
| -------------------------------------------------------------------------- |
| CLASS-WISE DISTRIBUTION CREATE                                             |
| -------------------------------------------------------------------------- |
| */                                                                         

public function distributionCreate(Request $request)
{
/*
|--------------------------------------------------------------------------
| ACADEMIC YEARS
|--------------------------------------------------------------------------
*/


$academicYears = KitTemplate::query()
    ->whereNotNull('academic_year')
    ->where('academic_year', '!=', '')
    ->select('academic_year')
    ->distinct()
    ->orderByDesc('academic_year')
    ->pluck('academic_year');

/*
|--------------------------------------------------------------------------
| CLASSES
|--------------------------------------------------------------------------
|
| Using Student admission_class because the existing Student module
| stores the student's class there.
|
*/

$classes = Student::query()
    ->where('status', 'active')
    ->whereNotNull('admission_class')
    ->where('admission_class', '!=', '')
    ->select('admission_class')
    ->distinct()
    ->orderBy('admission_class')
    ->pluck('admission_class')
    ->sort(function ($a, $b) {
        return strnatcasecmp((string) $a, (string) $b);
    })
    ->values();

/*
|--------------------------------------------------------------------------
| SECTIONS
|--------------------------------------------------------------------------
*/

$sections = Student::query()
    ->whereNotNull('section')
    ->where('section', '!=', '')
    ->select('section')
    ->distinct()
    ->orderBy('section')
    ->pluck('section');

/*
|--------------------------------------------------------------------------
| GOVERNMENT SCHEMES
|--------------------------------------------------------------------------
*/

$schemes = \App\Models\GovernmentScheme::query()
    ->where('status', 'active')
    ->orderBy('scheme_name')
    ->get();

/*
|--------------------------------------------------------------------------
| KIT TEMPLATES
|--------------------------------------------------------------------------
*/

$kitTemplates = KitTemplate::query()
    ->with([
        'scheme',
        'schoolClass',
        'items.supplyItem',
    ])
    ->where('status', 'active')
    ->orderByDesc('academic_year')
    ->orderBy('kit_name')
    ->get();

/*
|--------------------------------------------------------------------------
| RETURN VIEW
|--------------------------------------------------------------------------
*/

return view(
    'admin.student-supply-kits.distribution.create',
    compact(
        'academicYears',
        'classes',
        'sections',
        'schemes',
        'kitTemplates'
    )
);


}

 /*                                                                         |
| -------------------------------------------------------------------------- |
| LOAD CLASS-WISE STUDENTS                                                   |
| -------------------------------------------------------------------------- |
| */                                                                         


public function distributionStudents(Request $request)
{
    $validated = $request->validate([
        'academic_year' => [
            'required',
            'string',
            'max:20',
        ],

        'class' => [
            'required',
            'string',
            'max:100',
        ],

        'section' => [
            'nullable',
            'string',
            'max:100',
        ],

        'kit_template_id' => [
            'nullable',
            'integer',
            'exists:kit_templates,id',
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | ACADEMIC YEAR VARIANTS
    |--------------------------------------------------------------------------
    |
    | KitTemplate uses 2026-27
    | Student records use 2026-2027
    |
    */

    $academicYear = $validated['academic_year'];

    $academicYearVariants = [$academicYear];

    if (preg_match('/^(\d{4})-(\d{2})$/', $academicYear, $matches)) {
        $academicYearVariants[] =
            $matches[1] . '-' . substr($matches[1], 0, 2) . $matches[2];
    }

    if (preg_match('/^(\d{4})-(\d{4})$/', $academicYear, $matches)) {
        $academicYearVariants[] =
            $matches[1] . '-' . substr($matches[2], 2, 2);
    }

    $academicYearVariants = array_values(
        array_unique($academicYearVariants)
    );

    /*
    |--------------------------------------------------------------------------
    | STUDENTS
    |--------------------------------------------------------------------------
    */

    $studentsQuery = Student::query()
        ->where('status', 'active')
        ->where(
            'admission_class',
            $validated['class']
        )
        ->whereIn(
            'academic_year',
            $academicYearVariants
        );

    if (!empty($validated['section'])) {
        $studentsQuery->where(
            'section',
            $validated['section']
        );
    }

    $students = $studentsQuery
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->orderBy('last_name')
        ->get([
            'id',
            'student_id',
            'first_name',
            'middle_name',
            'last_name',
            'admission_class',
            'section',
            'academic_year',
        ]);

    /*
    |--------------------------------------------------------------------------
    | KIT TEMPLATE
    |--------------------------------------------------------------------------
    |
    | Kit is optional while loading students.
    |
    */

    $kitTemplateData = null;

    if (!empty($validated['kit_template_id'])) {

        $kitTemplate = KitTemplate::with([
            'scheme',
            'schoolClass',
            'items.supplyItem',
        ])
            ->where('status', 'active')
            ->where(
                'class',
                $validated['class']
            )
            ->where(
                'academic_year',
                $validated['academic_year']
            )
            ->findOrFail(
                $validated['kit_template_id']
            );

        /*
        |--------------------------------------------------------------------------
        | ALREADY ISSUED STUDENTS
        |--------------------------------------------------------------------------
        */

        $issuedStudentIds = StudentSupplyKit::query()
    ->where('academic_year', $validated['academic_year'])
    ->whereIn('status', ['pending', 'issued'])
    ->whereIn('student_id', $students->pluck('id'))
    ->pluck('student_id')
    ->map(fn ($id) => (int) $id)
    ->values();

        $kitTemplateData = [
            'id' => $kitTemplate->id,

            'kit_name' =>
                $kitTemplate->kit_name,

            'academic_year' =>
                $kitTemplate->academic_year,

            'scheme_name' =>
                $kitTemplate->scheme?->scheme_name,

            'scheme_code' =>
                $kitTemplate->scheme?->scheme_code,

            'items' =>
                $kitTemplate->items->map(function ($item) {
                    return [
                        'supply_item_id' =>
                            $item->supply_item_id,

                        'item_name' =>
                            $item->supplyItem?->item_name,

                        'item_code' =>
                            $item->supplyItem?->item_code,

                        'unit' =>
                            $item->supplyItem?->unit,

                        'quantity' =>
                            (int) $item->quantity,

                        'remarks' =>
                            $item->remarks,
                    ];
                })->values(),
        ];
    } else {
        $issuedStudentIds = collect();
    }

    /*
    |--------------------------------------------------------------------------
    | RETURN JSON
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'success' => true,

        'students' => $students->map(function ($student) use (
            $issuedStudentIds
        ) {
            $fullName = trim(
                implode(
                    ' ',
                    array_filter([
                        $student->first_name,
                        $student->middle_name,
                        $student->last_name,
                    ])
                )
            );

            return [
                'id' =>
                    $student->id,

                'student_id' =>
                    $student->student_id,

                'name' =>
                    $fullName ?: '-',

                'class' =>
                    $student->admission_class,

                'section' =>
                    $student->section,

                'academic_year' =>
                    $student->academic_year,

                'already_issued' =>
                    $issuedStudentIds->contains(
                        (int) $student->id
                    ),
            ];
        })->values(),

        'kit_template' =>
            $kitTemplateData,
    ]);
}


 /*                                                                         |
| -------------------------------------------------------------------------- |
| CLASS-WISE DISTRIBUTION STORE                                              |
| -------------------------------------------------------------------------- |
| */                                                                         

public function distributionStore(Request $request)
{
$validated = $request->validate([
'academic_year' => [
'required',
'string',
'max:20',
],


    'class' => [
        'required',
        'string',
        'max:100',
    ],

    'section' => [
        'nullable',
        'string',
        'max:100',
    ],

    'kit_template_id' => [
        'required',
        'integer',
        'exists:kit_templates,id',
    ],

    'distribution_date' => [
        'required',
        'date',
    ],

    'student_ids' => [
        'required',
        'array',
        'min:1',
    ],

    'student_ids.*' => [
        'required',
        'integer',
        'exists:students,id',
    ],

    'remarks' => [
        'nullable',
        'string',
        'max:1000',
    ],
]);

/*
|--------------------------------------------------------------------------
| LOAD KIT TEMPLATE
|--------------------------------------------------------------------------
*/

$kitTemplate = KitTemplate::with([
    'items.supplyItem',
])
    ->where('status', 'active')
    ->findOrFail(
        $validated['kit_template_id']
    );

/*
|--------------------------------------------------------------------------
| VALIDATE STUDENTS BELONG TO SELECTED CLASS / SECTION / YEAR
|--------------------------------------------------------------------------
*/


$academicYear = $validated['academic_year'];

$academicYearVariants = [$academicYear];

if (preg_match('/^(\d{4})-(\d{2})$/', $academicYear, $matches)) {
    $academicYearVariants[] =
        $matches[1] . '-' . substr($matches[1], 0, 2) . $matches[2];
}

if (preg_match('/^(\d{4})-(\d{4})$/', $academicYear, $matches)) {
    $academicYearVariants[] =
        $matches[1] . '-' . substr($matches[2], 2, 2);
}

$academicYearVariants = array_values(
    array_unique($academicYearVariants)
);

$studentsQuery = Student::query()
    ->where('status', 'active')
    ->whereIn(
        'id',
        $validated['student_ids']
    )
    ->where(
        'admission_class',
        $validated['class']
    )
    ->whereIn(
        'academic_year',
        $academicYearVariants
    );


if (!empty($validated['section'])) {
    $studentsQuery->where(
        'section',
        $validated['section']
    );
}

$students = $studentsQuery->get();

if (
    $students->count() !==
    count(array_unique($validated['student_ids']))
) {
    return back()
        ->withInput()
        ->withErrors([
            'student_ids' =>
                'One or more selected students do not belong to the selected class, section, or academic year.',
        ]);
}

/*
|--------------------------------------------------------------------------
| DISTRIBUTE
|--------------------------------------------------------------------------
*/

$createdCount = 0;
$skippedCount = 0;

DB::transaction(function () use (
    $validated,
    $kitTemplate,
    $students,
    &$createdCount,
    &$skippedCount
) {

    /*
    |--------------------------------------------------------------------------
    | PROCESS EACH STUDENT
    |--------------------------------------------------------------------------
    */

    foreach ($students as $student) {

        /*
        |--------------------------------------------------------------------------
        | DUPLICATE PREVENTION
        |--------------------------------------------------------------------------
        */

       $alreadyIssued = StudentSupplyKit::query()
    ->where('student_id', $student->id)
    ->where('academic_year', $validated['academic_year'])
    ->whereIn('status', ['pending', 'issued'])
    ->exists();

        if ($alreadyIssued) {
            $skippedCount++;
            continue;
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK STOCK BEFORE CREATING THE STUDENT KIT
        |--------------------------------------------------------------------------
        */

        foreach ($kitTemplate->items as $templateItem) {

            $requiredQuantity =
                (int) $templateItem->quantity;

            if ($requiredQuantity <= 0) {
                continue;
            }

            $stock = SupplyStock::where(
                'supply_item_id',
                $templateItem->supply_item_id
            )
                ->where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->lockForUpdate()
                ->first();

            if (!$stock) {

                $itemName =
                    $templateItem->supplyItem?->item_name
                    ?? 'Selected supply item';

                throw new \RuntimeException(
                    $itemName
                    . ': stock record not found for academic year '
                    . $validated['academic_year']
                    . '. Please receive stock first.'
                );
            }

            if (
                $requiredQuantity >
                $stock->quantity
            ) {

                $itemName =
                    $templateItem->supplyItem?->item_name
                    ?? 'Selected supply item';

                throw new \RuntimeException(
                    $itemName
                    . ': insufficient stock. Required '
                    . $requiredQuantity
                    . ', available '
                    . $stock->quantity
                    . '.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CREATE STUDENT SUPPLY KIT
        |--------------------------------------------------------------------------
        */

        $studentSupplyKit =
            StudentSupplyKit::create([
                'student_id' =>
                    $student->id,

                'kit_template_id' =>
                    $kitTemplate->id,

                'academic_year' =>
                    $validated['academic_year'],

                'distribution_date' =>
                    $validated['distribution_date'],

                'status' =>
                    'issued',

                'remarks' =>
                    $validated['remarks'] ?? null,
            ]);

        /*
        |--------------------------------------------------------------------------
        | CREATE ITEMS + DEDUCT STOCK
        |--------------------------------------------------------------------------
        */

        foreach ($kitTemplate->items as $templateItem) {

            $quantity =
                (int) $templateItem->quantity;

            if ($quantity <= 0) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CREATE STUDENT KIT ITEM
            |--------------------------------------------------------------------------
            */

            StudentSupplyKitItem::create([
                'student_supply_kit_id' =>
                    $studentSupplyKit->id,

                'supply_item_id' =>
                    $templateItem->supply_item_id,

                'quantity' =>
                    $quantity,

                'issued_quantity' =>
                    $quantity,

                'remarks' =>
                    $templateItem->remarks,
            ]);

            /*
            |--------------------------------------------------------------------------
            | LOCK STOCK
            |--------------------------------------------------------------------------
            */

            $stock = SupplyStock::where(
                'supply_item_id',
                $templateItem->supply_item_id
            )
                ->where(
                    'academic_year',
                    $validated['academic_year']
                )
                ->lockForUpdate()
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | DEDUCT STOCK
            |--------------------------------------------------------------------------
            */

            $stock->quantity -= $quantity;

            $stock->save();

            /*
            |--------------------------------------------------------------------------
            | STOCK TRANSACTION
            |--------------------------------------------------------------------------
            */

            SupplyStockTransaction::create([
                'supply_item_id' =>
                    $templateItem->supply_item_id,

                'academic_year' =>
                    $validated['academic_year'],

                'transaction_type' =>
                    'issue',

                'quantity' =>
                    $quantity,

                'balance_quantity' =>
                    $stock->quantity,

                'reference_type' =>
                    'student_supply_kit',

                'reference_id' =>
                    $studentSupplyKit->id,

                'remarks' =>
                    'Issued through class-wise student supply kit distribution.',

                'created_by' =>
                    auth()->id(),
            ]);
        }

        $createdCount++;
    }
});

/*
|--------------------------------------------------------------------------
| RESULT MESSAGE
|--------------------------------------------------------------------------
*/

$message =
    $createdCount
    . ' student supply kit(s) distributed successfully.';

if ($skippedCount > 0) {
    $message .=
        ' '
        . $skippedCount
        . ' student(s) were skipped because the kit was already issued.';
}

return redirect()
    ->route(
        'admin.student-supply-kits.index'
    )
    ->with(
        'success',
        $message
    );

}

}