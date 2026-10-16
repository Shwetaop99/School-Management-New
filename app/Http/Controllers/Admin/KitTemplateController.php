<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Class\SchoolClass;
use App\Models\GovernmentScheme;
use App\Models\KitTemplate;
use App\Models\KitTemplateItem;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KitTemplateController extends Controller
{
    /**
     * Display kit templates.
     */
    public function index(Request $request)
    {
        $query = KitTemplate::with([
            'scheme',
            'schoolClass',
            'items.supplyItem',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('kit_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhereHas('schoolClass', function ($classQuery) use ($search) {
    $classQuery
        ->where('class_name', 'like', "%{$search}%")
        ->orWhere('section', 'like', "%{$search}%");
})
                    ->orWhereHas('scheme', function ($schemeQuery) use ($search) {
                        $schemeQuery
                            ->where('scheme_name', 'like', "%{$search}%")
                            ->orWhere('scheme_code', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Class Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        /*
        |--------------------------------------------------------------------------
        | Academic Year Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Kit Templates
        |--------------------------------------------------------------------------
        */
        $kitTemplates = $query
            ->withCount('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Class Dropdown
        |--------------------------------------------------------------------------
        */
        $classes = SchoolClass::where('status', 1)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Academic Year Dropdown
        |--------------------------------------------------------------------------
        */
        $academicYears = KitTemplate::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */
        $stats = [
            'total' => KitTemplate::count(),

            'active' => KitTemplate::where(
                'status',
                'active'
            )->count(),

            'inactive' => KitTemplate::where(
                'status',
                'inactive'
            )->count(),

            'items' => KitTemplateItem::count(),
        ];

        return view(
            'admin.kit-templates.index',
            compact(
                'kitTemplates',
                'classes',
                'academicYears',
                'stats'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Government Schemes
        |--------------------------------------------------------------------------
        */
        $schemes = GovernmentScheme::where('status', 'active')
            ->orderBy('scheme_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Supply Items
        |--------------------------------------------------------------------------
        */
        $supplyItems = SupplyItem::where('status', 'active')
            ->orderBy('item_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */
        $classes = SchoolClass::where('status', 1)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view(
            'admin.kit-templates.create',
            compact(
                'schemes',
                'supplyItems',
                'classes'
            )
        );
    }

    /**
     * Store kit template with items.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scheme_id' => [
                'required',
                'integer',
                'exists:government_schemes,id',
            ],


            'kit_name' => [
                'required',
                'string',
                'max:255',
            ],

            'class' => [
                'nullable',
                'string',
                'max:100',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'items' => [
                'required',
                'array',
                'min:1',
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

            'items.*.remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $kitTemplate = KitTemplate::create([
                'scheme_id' => $validated['scheme_id'],
                'class_id' => $validated['class_id'],
                'kit_name' => $validated['kit_name'],
                'academic_year' => $validated['academic_year'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] as $item) {
                KitTemplateItem::create([
                    'kit_template_id' => $kitTemplate->id,
                    'supply_item_id' => $item['supply_item_id'],
                    'quantity' => $item['quantity'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.kit-templates.index')
            ->with(
                'success',
                'Kit template created successfully.'
            );
    }

    /**
     * Display kit template.
     */
    public function show(KitTemplate $kitTemplate)
    {
        $kitTemplate->load([
            'scheme',
            'schoolClass',
            'items.supplyItem',
        ]);

        return view(
            'admin.kit-templates.show',
            compact('kitTemplate')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(KitTemplate $kitTemplate)
    {
        $kitTemplate->load([
            'scheme',
            'schoolClass',
            'items.supplyItem',
        ]);

        $schemes = GovernmentScheme::where('status', 'active')
            ->orderBy('scheme_name')
            ->get();

        $supplyItems = SupplyItem::where('status', 'active')
            ->orderBy('item_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        | Keep the same status condition used in create().
        */
        $classes = SchoolClass::where('status', 1)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view(
            'admin.kit-templates.edit',
            compact(
                'kitTemplate',
                'schemes',
                'supplyItems',
                'classes'
            )
        );
    }

    /**
     * Update kit template and items.
     */
    public function update(
        Request $request,
        KitTemplate $kitTemplate
    ) {
        $validated = $request->validate([
            'scheme_id' => [
                'required',
                'integer',
                'exists:government_schemes,id',
            ],

            'kit_name' => [
                'required',
                'string',
                'max:255',
            ],

            'class' => [
                'nullable',
                'string',
                'max:100',
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'items' => [
                'required',
                'array',
                'min:1',
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

            'items.*.remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $kitTemplate
        ) {

            $kitTemplate->update([
                'scheme_id' => $validated['scheme_id'],
                'class_id' => $validated['class_id'],
                'kit_name' => $validated['kit_name'],
                'academic_year' => $validated['academic_year'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Remove Existing Items
            |--------------------------------------------------------------------------
            */
            $kitTemplate->items()->delete();

            /*
            |--------------------------------------------------------------------------
            | Add Updated Items
            |--------------------------------------------------------------------------
            */
            foreach ($validated['items'] as $item) {
                KitTemplateItem::create([
                    'kit_template_id' => $kitTemplate->id,
                    'supply_item_id' => $item['supply_item_id'],
                    'quantity' => $item['quantity'],
                    'remarks' => $item['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route(
                'admin.kit-templates.show',
                $kitTemplate
            )
            ->with(
                'success',
                'Kit template updated successfully.'
            );
    }

    /**
     * Delete kit template.
     */
    public function destroy(KitTemplate $kitTemplate)
    {
        $kitTemplate->delete();

        return redirect()
            ->route('admin.kit-templates.index')
            ->with(
                'success',
                'Kit template deleted successfully.'
            );
    }
}


