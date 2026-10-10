<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KitTemplate;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KitTemplateController extends Controller
{
    /**
     * Display kit templates.
     */
    public function index(Request $request)
    {
        $query = KitTemplate::with('items.supplyItem')
            ->withCount('items')
            ->latest();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('kit_name', 'like', "%{$search}%")
                    ->orWhere('class', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class')) {
            $query->where('class', $request->class);
        }

        if ($request->filled('academic_year')) {
            $query->where(
                'academic_year',
                $request->academic_year
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $kitTemplates = $query
            ->paginate(10)
            ->withQueryString();

        $classes = KitTemplate::query()
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->distinct()
            ->orderBy('class')
            ->pluck('class');

        $academicYears = KitTemplate::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

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

            'items' => DB::table('kit_template_items')->count(),
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
    $supplyItems = SupplyItem::query()
        ->where('status', 'active')
        ->orderBy('item_name')
        ->get();

    $classes = [
        'Nursery',
        'LKG',
        'UKG',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
    ];

    return view(
        'admin.kit-templates.create',
        compact('supplyItems', 'classes')
    );
}


    /**
     * Store a kit template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kit_name' => [
                'required',
                'string',
                'max:255',
            ],

            'class' => [
                'required',
                'string',
                'max:100',
            ],

            'academic_year' => [
                'nullable',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'items' => [
                'nullable',
                'array',
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

            'items.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $kitTemplate = KitTemplate::create([
                'kit_name' => $validated['kit_name'],
                'class' => $validated['class'],
                'academic_year' =>
                    $validated['academic_year'] ?? null,
                'description' =>
                    $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            foreach ($validated['items'] ?? [] as $item) {

                $kitTemplate->items()->create([
                    'supply_item_id' =>
                        $item['supply_item_id'],

                    'quantity' =>
                        $item['quantity'],

                    'remarks' =>
                        $item['remarks'] ?? null,
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
     * Display a kit template.
     */
    public function show(KitTemplate $kitTemplate)
    {
        $kitTemplate->load([
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
            'items.supplyItem',
        ]);

        $supplyItems = SupplyItem::query()
            ->where('status', 'active')
            ->orderBy('item_name')
            ->get();

        $classes = [
            'Nursery',
            'LKG',
            'UKG',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '10',
            '11',
            '12',
        ];

        return view(
            'admin.kit-templates.edit',
            compact(
                'kitTemplate',
                'supplyItems',
                'classes'
            )
        );
    }

    /**
     * Update a kit template.
     */
    public function update(
        Request $request,
        KitTemplate $kitTemplate
    ) {
        $validated = $request->validate([
            'kit_name' => [
                'required',
                'string',
                'max:255',
            ],

            'class' => [
                'required',
                'string',
                'max:100',
            ],

            'academic_year' => [
                'nullable',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'items' => [
                'nullable',
                'array',
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

            'items.*.remarks' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $kitTemplate
        ) {

            $kitTemplate->update([
                'kit_name' => $validated['kit_name'],
                'class' => $validated['class'],
                'academic_year' =>
                    $validated['academic_year'] ?? null,
                'description' =>
                    $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            /*
             * Remove old template items.
             */
            $kitTemplate->items()->delete();

            /*
             * Add current template items.
             */
            foreach ($validated['items'] ?? [] as $item) {

                $kitTemplate->items()->create([
                    'supply_item_id' =>
                        $item['supply_item_id'],

                    'quantity' =>
                        $item['quantity'],

                    'remarks' =>
                        $item['remarks'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.kit-templates.index')
            ->with(
                'success',
                'Kit template updated successfully.'
            );
    }

    /**
     * Delete a kit template.
     */
    public function destroy(KitTemplate $kitTemplate)
    {
        /*
         * Do not delete a template already used
         * by issued student supply kits.
         */
        if ($kitTemplate->studentSupplyKits()->exists()) {
            return redirect()
                ->route('admin.kit-templates.index')
                ->with(
                    'error',
                    'This kit template cannot be deleted because it is already used by student supply kits.'
                );
        }

        DB::transaction(function () use ($kitTemplate) {

            $kitTemplate->items()->delete();

            $kitTemplate->delete();
        });

        return redirect()
            ->route('admin.kit-templates.index')
            ->with(
                'success',
                'Kit template deleted successfully.'
            );
    }
}