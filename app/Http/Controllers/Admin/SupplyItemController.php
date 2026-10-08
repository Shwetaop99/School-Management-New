<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplyItemController extends Controller
{
    /**
     * Display a listing of supply items.
     */
    public function index(Request $request)
    {
        $query = SupplyItem::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('item_name', 'like', "%{$search}%")
                    ->orWhere('item_code', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $supplyItems = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'admin.supply-items.index',
            compact('supplyItems')
        );
    }

    /**
     * Show the form for creating a new supply item.
     */
    public function create()
    {
        return view('admin.supply-items.create');
    }

    /**
     * Store a newly created supply item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'item_code' => [
                'nullable',
                'string',
                'max:100',
                'unique:supply_items,item_code',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
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
     * Display the specified supply item.
     */
    public function show(SupplyItem $supplyItem)
    {
        $supplyItem->load([
            'kitTemplateItems.kitTemplate'
        ]);

        return view(
            'admin.supply-items.show',
            compact('supplyItem')
        );
    }

    /**
     * Show the form for editing the specified supply item.
     */
    public function edit(SupplyItem $supplyItem)
    {
        return view(
            'admin.supply-items.edit',
            compact('supplyItem')
        );
    }

    /**
     * Update the specified supply item.
     */
    public function update(
        Request $request,
        SupplyItem $supplyItem
    ) {
        $validated = $request->validate([
            'item_name' => [
                'required',
                'string',
                'max:255',
            ],

            'item_code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('supply_items', 'item_code')
                    ->ignore($supplyItem->id),
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'description' => [
                'nullable',
                'string',
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
     * Remove the specified supply item.
     */
    public function destroy(SupplyItem $supplyItem)
    {
        // Do not delete an item already used in a kit template.
        if ($supplyItem->kitTemplateItems()->exists()) {
            return redirect()
                ->route('admin.supply-items.index')
                ->with(
                    'error',
                    'This supply item cannot be deleted because it is already used in a kit template.'
                );
        }

        $supplyItem->delete();

        return redirect()
            ->route('admin.supply-items.index')
            ->with('success', 'Supply item deleted successfully.');
    }
}