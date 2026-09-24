<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StaffCategoryController extends Controller
{
    /**
     * Display all staff categories.
     */
    public function index()
    {
        $categories = StaffCategory::latest()->get();

        return view('admin.staff-categories.index', compact('categories'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.staff-categories.create');
    }

    /**
     * Store new category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:staff_categories,name',
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);

        StaffCategory::create($validated);

        return redirect()
            ->route('admin.staff-categories.index')
            ->with('success', 'Staff category created successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(StaffCategory $staffCategory)
    {
        return view(
            'admin.staff-categories.edit',
            compact('staffCategory')
        );
    }

    /**
     * Update category.
     */
    public function update(
        Request $request,
        StaffCategory $staffCategory
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('staff_categories', 'name')
                    ->ignore($staffCategory->id),
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);

        $staffCategory->update($validated);

        return redirect()
            ->route('admin.staff-categories.index')
            ->with('success', 'Staff category updated successfully.');
    }

    /**
     * Delete category.
     */
    public function destroy(StaffCategory $staffCategory)
    {
        $staffCategory->delete();

        return redirect()
            ->route('admin.staff-categories.index')
            ->with('success', 'Staff category deleted successfully.');
    }
}