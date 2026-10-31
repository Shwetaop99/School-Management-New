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
     * Get classes for dropdowns.
     *
     * Do not assume that every project stores active status as integer 1.
     * Fetching all class records also helps diagnose missing dropdown values.
     */
    private function getClasses()
    {
        return SchoolClass::query()
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();
    }

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

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('kit_name', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%")
                    ->orWhereHas('schoolClass', function ($classQuery) use ($search) {
                        $classQuery->where('class_name', 'like', "%{$search}%")
                            ->orWhere('section', 'like', "%{$search}%");
                    })
                    ->orWhereHas('scheme', function ($schemeQuery) use ($search) {
                        $schemeQuery->where('scheme_name', 'like', "%{$search}%")
                            ->orWhere('scheme_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->input('academic_year'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $kitTemplates = $query
            ->withCount('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $classes = $this->getClasses();

        $academicYears = KitTemplate::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        $stats = [
            'total' => KitTemplate::count(),
            'active' => KitTemplate::where('status', 'active')->count(),
            'inactive' => KitTemplate::where('status', 'inactive')->count(),
            'items' => KitTemplateItem::count(),
        ];

        return view('admin.kit-templates.index', compact(
            'kitTemplates',
            'classes',
            'academicYears',
            'stats'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $schemes = GovernmentScheme::query()
            ->where('status', 'active')
            ->orderBy('scheme_name')
            ->get();

        $supplyItems = SupplyItem::query()
            ->where('status', 'active')
            ->orderBy('item_name')
            ->get();

        $classes = $this->getClasses();

        return view('admin.kit-templates.create', compact(
            'schemes',
            'supplyItems',
            'classes'
        ));
    }

    /**
     * Validate kit data shared by store and update.
     */
    private function validateKit(Request $request): array
    {
        return $request->validate([
            'scheme_id' => [
                'required',
                'integer',
                Rule::exists('government_schemes', 'id'),
            ],

            'kit_name' => [
                'required',
                'string',
                'max:255',
            ],

            // The form must submit the selected SchoolClass ID.
            'class_id' => [
                'required',
                'integer',
                Rule::exists((new SchoolClass())->getTable(), 'id'),
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
                Rule::in(['active', 'inactive']),
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.supply_item_id' => [
                'required',
                'integer',
                Rule::exists('supply_items', 'id'),
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
    }

    /**
     * Store kit template with its items.
     */
    public function store(Request $request)
    {
        $validated = $this->validateKit($request);

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
            ->with('success', 'Government kit created successfully.');
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

        return view('admin.kit-templates.show', compact('kitTemplate'));
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

        $schemes = GovernmentScheme::query()
            ->where('status', 'active')
            ->orderBy('scheme_name')
            ->get();

        $supplyItems = SupplyItem::query()
            ->where(function ($query) {
                $query->where('status', 'active')
                    ->orWhereNull('status');
            })
            ->orderBy('item_name')
            ->get();

        $classes = $this->getClasses();

        // Include existing kit items even if their supply item is inactive.
        $existingSupplyItemIds = $kitTemplate->items
            ->pluck('supply_item_id');

        if ($existingSupplyItemIds->isNotEmpty()) {
            $existingItems = SupplyItem::whereIn(
                'id',
                $existingSupplyItemIds
            )->get();

            $supplyItems = $supplyItems
                ->concat($existingItems)
                ->unique('id')
                ->sortBy('item_name')
                ->values();
        }

        return view('admin.kit-templates.edit', compact(
            'kitTemplate',
            'schemes',
            'supplyItems',
            'classes'
        ));
    }

    /**
     * Update kit template and its items.
     */
    public function update(Request $request, KitTemplate $kitTemplate)
    {
        $validated = $this->validateKit($request);

        DB::transaction(function () use ($validated, $kitTemplate) {
            $kitTemplate->update([
                'scheme_id' => $validated['scheme_id'],
                'class_id' => $validated['class_id'],
                'kit_name' => $validated['kit_name'],
                'academic_year' => $validated['academic_year'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            $kitTemplate->items()->delete();

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
    ->route('admin.kit-templates.edit', $kitTemplate)
    ->with('success', 'Government Kit updated successfully!');
    }

    /**
     * Delete kit template.
     */
    public function destroy(KitTemplate $kitTemplate)
    {
        $kitTemplate->delete();

        return redirect()
            ->route('admin.kit-templates.index')
            ->with('success', 'Government kit deleted successfully.');
    }
}
