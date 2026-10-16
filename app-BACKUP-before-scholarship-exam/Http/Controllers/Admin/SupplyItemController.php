<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplyItemController extends Controller
{
    /**
     * Display supply items.
     */
    public function index(Request $request)
    {
        $query = SupplyItem::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                    ->orWhere('item_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalItems = SupplyItem::count();

        $activeItems = SupplyItem::where('status', 'active')->count();

        $lowStockItems = SupplyItem::whereColumn(
            'quantity_in_stock',
            '<=',
            'minimum_stock'
        )->count();

        $totalStock = SupplyItem::sum('quantity_in_stock');

        $categories = SupplyItem::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.supply-items.index', compact(
            'items',
            'totalItems',
            'activeItems',
            'lowStockItems',
            'totalStock',
            'categories'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $categories = [
            'Stationery',
            'Books',
            'Uniform',
            'Footwear',
            'Bag',
            'Accessories',
            'Sports',
            'Other',
        ];

        $units = [
            'Piece',
            'Pair',
            'Set',
            'Box',
            'Pack',
            'Bottle',
            'Dozen',
        ];

        return view('admin.supply-items.create', compact(
            'categories',
            'units'
        ));
    }

    /**
     * Store supply item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:100',
                'unique:supply_items,item_code',
            ],

            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'quantity_in_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        SupplyItem::create($validated);

        return redirect()
            ->route('admin.supply-items.index')
            ->with('success', 'Supply item created successfully.');
    }

    /**
     * Display supply item.
     */
    public function show(SupplyItem $supplyItem)
    {
        return view('admin.supply-items.show', compact('supplyItem'));
    }

    /**
     * Show edit form.
     */
    public function edit(SupplyItem $supplyItem)
    {
        $categories = [
            'Stationery',
            'Books',
            'Uniform',
            'Footwear',
            'Bag',
            'Accessories',
            'Sports',
            'Other',
        ];

        $units = [
            'Piece',
            'Pair',
            'Set',
            'Box',
            'Pack',
            'Bottle',
            'Dozen',
        ];

        return view('admin.supply-items.edit', compact(
            'supplyItem',
            'categories',
            'units'
        ));
    }

    /**
     * Update supply item.
     */
    public function update(Request $request, SupplyItem $supplyItem)
    {
        $validated = $request->validate([
            'item_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('supply_items', 'item_code')
                    ->ignore($supplyItem->id),
            ],

            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'quantity_in_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'unit_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $supplyItem->update($validated);

        return redirect()
            ->route('admin.supply-items.index')
            ->with('success', 'Supply item updated successfully.');
    }

    /**
     * Delete supply item.
     */
    public function destroy(SupplyItem $supplyItem)
    {
        // Prevent deletion if item is already used
        if (
            $supplyItem->kitTemplateItems()->exists() ||
            $supplyItem->studentSupplyKitItems()->exists()
        ) {
            return redirect()
                ->route('admin.supply-items.index')
                ->with(
                    'error',
                    'This supply item cannot be deleted because it is already used in a kit.'
                );
        }

        $supplyItem->delete();

        return redirect()
            ->route('admin.supply-items.index')
            ->with('success', 'Supply item deleted successfully.');
    }
}