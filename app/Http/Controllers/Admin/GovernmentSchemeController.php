<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GovernmentScheme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GovernmentSchemeController extends Controller
{
    /**
     * Display all government schemes.
     */
    public function index(Request $request)
    {
        $query = GovernmentScheme::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('scheme_name', 'like', "%{$search}%")
                    ->orWhere('scheme_code', 'like', "%{$search}%")
                    ->orWhere('government', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $schemes = $query
            ->withCount('kitTemplates')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $academicYears = GovernmentScheme::query()
            ->select('academic_year')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('admin.government-schemes.index', compact(
            'schemes',
            'academicYears'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.government-schemes.create');
    }

    /**
     * Store new government scheme.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'scheme_name' => [
                'required',
                'string',
                'max:255',
            ],

            'scheme_code' => [
                'nullable',
                'string',
                'max:100',
                'unique:government_schemes,scheme_code',
            ],

            'government' => [
                'nullable',
                'string',
                'max:255',
            ],

            'department' => [
                'nullable',
                'string',
                'max:255',
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

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        GovernmentScheme::create($validated);

        return redirect()
            ->route('admin.government-schemes.index')
            ->with('success', 'Government scheme created successfully.');
    }

    /**
     * Display a scheme.
     */
    public function show(GovernmentScheme $governmentScheme)
    {
        $governmentScheme->loadCount('kitTemplates');

        $governmentScheme->load([
            'kitTemplates' => function ($query) {
                $query->withCount('items');
            },
        ]);

        return view(
            'admin.government-schemes.show',
            compact('governmentScheme')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(GovernmentScheme $governmentScheme)
    {
        return view(
            'admin.government-schemes.edit',
            compact('governmentScheme')
        );
    }

    /**
     * Update scheme.
     */
    public function update(
        Request $request,
        GovernmentScheme $governmentScheme
    ) {
        $validated = $request->validate([
            'scheme_name' => [
                'required',
                'string',
                'max:255',
            ],

            'scheme_code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique(
                    'government_schemes',
                    'scheme_code'
                )->ignore($governmentScheme->id),
            ],

            'government' => [
                'nullable',
                'string',
                'max:255',
            ],

            'department' => [
                'nullable',
                'string',
                'max:255',
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

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'status' => [
                'required',
                Rule::in(['active', 'inactive']),
            ],
        ]);

        $governmentScheme->update($validated);

        return redirect()
            ->route('admin.government-schemes.index')
            ->with('success', 'Government scheme updated successfully.');
    }

    /**
     * Delete scheme.
     */
    public function destroy(GovernmentScheme $governmentScheme)
    {
        if ($governmentScheme->kitTemplates()->exists()) {
            return redirect()
                ->route('admin.government-schemes.index')
                ->with(
                    'error',
                    'This scheme cannot be deleted because kit templates are linked to it.'
                );
        }

        $governmentScheme->delete();

        return redirect()
            ->route('admin.government-schemes.index')
            ->with('success', 'Government scheme deleted successfully.');
    }
}