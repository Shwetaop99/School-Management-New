<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeType;
use Illuminate\Http\Request;

class FeeTypeController extends Controller
{
    /**
     * Display all fee types.
     */
    public function index(Request $request)
    {
        $query = FeeType::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('frequency', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $feeTypes = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalTypes = FeeType::count();

        $activeTypes = FeeType::where('status', 'Active')->count();

        $inactiveTypes = FeeType::where('status', 'Inactive')->count();

        return view(
            'admin.fees.fee-types.index',
            compact(
                'feeTypes',
                'totalTypes',
                'activeTypes',
                'inactiveTypes'
            )
        );
    }

    /**
     * Show create fee type form.
     */
    public function create()
    {
        $feeTypeOptions = [
            'Tuition Fee',
            'Admission Fee',
            'Examination Fee',
            'Annual Fee',
            'Computer Fee',
            'Library Fee',
            'Sports Fee',
            'Activity Fee',
            'Transport Fee',
            'Laboratory Fee',
            'Uniform Fee',
            'Books Fee',
            'Other Fee',
        ];

        $categories = [
            'Academic',
            'Administrative',
            'Facility',
            'Transport',
            'Other',
        ];

        $frequencies = [
            'One Time',
            'Monthly',
            'Quarterly',
            'Half Yearly',
            'Yearly',
        ];

        return view(
            'admin.fees.fee-types.create',
            compact(
                'feeTypeOptions',
                'categories',
                'frequencies'
            )
        );
    }

    /**
     * Store a new fee type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'category' => 'required|string|max:100',

            'frequency' => 'required|string|max:100',

            'amount' => 'required|numeric|min:0|max:99999999.99',

            'description' => 'nullable|string|max:1000',

            'status' => 'required|in:Active,Inactive',
        ]);

        FeeType::create($validated);

        return redirect()
            ->route('admin.fees.fee-types.index')
            ->with(
                'success',
                'Fee type created successfully.'
            );
    }

    /**
     * Show edit fee type form.
     */
    public function edit(FeeType $feeType)
    {
        $feeTypeOptions = [
            'Tuition Fee',
            'Admission Fee',
            'Examination Fee',
            'Annual Fee',
            'Computer Fee',
            'Library Fee',
            'Sports Fee',
            'Activity Fee',
            'Transport Fee',
            'Laboratory Fee',
            'Uniform Fee',
            'Books Fee',
            'Other Fee',
        ];

        $categories = [
            'Academic',
            'Administrative',
            'Facility',
            'Transport',
            'Other',
        ];

        $frequencies = [
            'One Time',
            'Monthly',
            'Quarterly',
            'Half Yearly',
            'Yearly',
        ];

        return view(
            'admin.fees.fee-types.edit',
            compact(
                'feeType',
                'feeTypeOptions',
                'categories',
                'frequencies'
            )
        );
    }

    /**
     * Update an existing fee type.
     */
    public function update(Request $request, FeeType $feeType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'category' => 'required|string|max:100',

            'frequency' => 'required|string|max:100',

            'amount' => 'required|numeric|min:0|max:99999999.99',

            'description' => 'nullable|string|max:1000',

            'status' => 'required|in:Active,Inactive',
        ]);

        $feeType->update($validated);

        return redirect()
            ->route('admin.fees.fee-types.index')
            ->with(
                'success',
                'Fee type updated successfully.'
            );
    }

    /**
     * Display a single fee type.
     */
    public function show(FeeType $feeType)
    {
        return view(
            'admin.fees.fee-types.show',
            compact('feeType')
        );
    }

    /**
     * Delete a fee type.
     */
    public function destroy(FeeType $feeType)
    {
        $feeType->delete();

        return redirect()
            ->route('admin.fees.fee-types.index')
            ->with(
                'success',
                'Fee type deleted successfully.'
            );
    }
}