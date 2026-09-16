<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')
            ->latest()
            ->paginate(10);

        return view('admin.library.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.library.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.library.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load('books');

        return view('admin.library.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.library.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'status' => 'required|in:Active,Inactive',
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.library.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->exists()) {
            return redirect()
                ->route('admin.library.categories.index')
                ->with('error', 'This category cannot be deleted because books are assigned to it.');
        }

        $category->delete();

        return redirect()
            ->route('admin.library.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}