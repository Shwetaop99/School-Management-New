<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use App\Models\Sports\Achievements\Achievement;
use App\Models\Class\SchoolClass;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $query = Achievement::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('student_name', 'like', "%{$search}%")
                    ->orWhere('sport_name', 'like', "%{$search}%")
                    ->orWhere('competition_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sport_name')) {
            $query->where('sport_name', $request->sport_name);
        }

        if ($request->filled('academic_year')) {
            $query->where('academic_year', $request->academic_year);
        }

        $achievements = $query
            ->latest('achievement_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $sports = Achievement::query()
            ->whereNotNull('sport_name')
            ->where('sport_name', '!=', '')
            ->distinct()
            ->orderBy('sport_name')
            ->pluck('sport_name');

        $academicYears = Achievement::query()
            ->whereNotNull('academic_year')
            ->where('academic_year', '!=', '')
            ->distinct()
            ->orderByDesc('academic_year')
            ->pluck('academic_year');

        return view('admin.sports.achievements.index', compact(
            'achievements',
            'sports',
            'academicYears'
        ));
    }

    public function create()
    {
        $classes = SchoolClass::query()
            ->where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view('admin.sports.achievements.create', compact(
            'classes'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'student_name' => 'nullable|string|max:255',
            'sport_name' => 'required|string|max:255',
            'achievement_type' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'competition_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'achievement_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        Achievement::create($validated);

        return redirect()
            ->route('admin.sports.achievements.index')
            ->with('success', 'Achievement added successfully.');
    }

    public function show(Achievement $achievement)
    {
        return view('admin.sports.achievements.show', compact(
            'achievement'
        ));
    }

    public function edit(Achievement $achievement)
    {
        $classes = SchoolClass::query()
            ->where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view('admin.sports.achievements.edit', compact(
            'achievement',
            'classes'
        ));
    }

    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'student_name' => 'nullable|string|max:255',
            'sport_name' => 'required|string|max:255',
            'achievement_type' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'competition_name' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'achievement_date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $achievement->update($validated);

        return redirect()
            ->route('admin.sports.achievements.index')
            ->with('success', 'Achievement updated successfully.');
    }

    public function destroy(Achievement $achievement)
    {
        $achievement->delete();

        return redirect()
            ->route('admin.sports.achievements.index')
            ->with('success', 'Achievement deleted successfully.');
    }
}