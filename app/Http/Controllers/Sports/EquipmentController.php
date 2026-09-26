<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use App\Models\Sports\Equipment\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Equipment::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('equipment_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $equipment = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Equipment::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.sports.equipment.index', compact(
            'equipment',
            'categories'
        ));
    }

    public function create()
    {
        // Load existing equipment categories for the Add Equipment form
        $categories = Equipment::query()
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.sports.equipment.create', compact(
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'equipment_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',

            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0|lte:quantity',

            'unit' => 'nullable|string|max:50',

            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',

            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'condition' => 'required|in:new,good,fair,damaged',
            'status' => 'required|in:active,inactive',
        ]);

        Equipment::create($validated);

        return redirect()
            ->route('admin.sports.equipment.index')
            ->with('success', 'Sports equipment added successfully.');
    }

    public function show(Equipment $equipment)
    {
        return view('admin.sports.equipment.show', compact(
            'equipment'
        ));
    }

    public function edit(Equipment $equipment)
    {
        return view('admin.sports.equipment.edit', compact(
            'equipment'
        ));
    }

    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'equipment_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',

            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0|lte:quantity',

            'unit' => 'nullable|string|max:50',

            'purchase_price' => 'nullable|numeric|min:0',
            'purchase_date' => 'nullable|date',

            'supplier' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',

            'description' => 'nullable|string',

            'condition' => 'required|in:new,good,fair,damaged',
            'status' => 'required|in:active,inactive',
        ]);

        $equipment->update($validated);

        return redirect()
            ->route('admin.sports.equipment.index')
            ->with('success', 'Sports equipment updated successfully.');
    }

    public function destroy(Equipment $equipment)
    {
        $equipment->delete();

        return redirect()
            ->route('admin.sports.equipment.index')
            ->with('success', 'Sports equipment deleted successfully.');
    }
}