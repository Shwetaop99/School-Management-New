<?php

namespace App\Http\Controllers\Sports;

use App\Http\Controllers\Controller;
use App\Models\Sports\Games\Game;
use App\Models\Class\SchoolClass;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display all games/events.
     */
    public function index(Request $request)
    {
        $query = Game::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sport_name', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%")
                    ->orWhere('organizer', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sport filter
        if ($request->filled('sport_name')) {
            $query->where('sport_name', $request->sport_name);
        }

        // Paginated records
        $games = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Sports list for filter
        $sports = Game::query()
            ->whereNotNull('sport_name')
            ->where('sport_name', '!=', '')
            ->distinct()
            ->orderBy('sport_name')
            ->pluck('sport_name');

        return view('admin.sports.games.index', compact(
            'games',
            'sports'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        /*
        |--------------------------------------------------------------------------
        | Fetch active classes from Class Module
        |--------------------------------------------------------------------------
        | Each record contains:
        | class_name
        | section
        | academic_year
        | status
        |
        | We only fetch active classes.
        */
        $classes = SchoolClass::query()
            ->where('status', true)
            ->orderBy('class_name')
            ->orderBy('section')
            ->get();

        return view('admin.sports.games.create', compact(
            'classes'
        ));
    }

    /**
     * Store new game/event.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sport_name' => 'required|string|max:255',
            'event_type' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'required|date_format:H:i|after_or_equal:start_time',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        Game::create($validated);

        return redirect()
            ->route('admin.sports.games.index')
            ->with('success', 'Game/Event added successfully.');
    }

    /**
     * Display a short event notice.
     */
    public function show(Game $game)
    {
        return view('admin.sports.games.show', compact('game'));
    }

    /**
     * Show edit form.
     */
    public function edit(Game $game)
    {
        return view('admin.sports.games.edit', compact('game'));
    }

    /**
     * Update game/event.
     */
    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'sport_name' => 'required|string|max:255',
            'event_type' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'class' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:100',
            'event_date' => 'nullable|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable|after_or_equal:start_time',
            'venue' => 'nullable|string|max:255',
            'organizer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
        ]);

        $game->update($validated);

        return redirect()
            ->route('admin.sports.games.index')
            ->with('success', 'Game/Event updated successfully.');
    }

    /**
     * Delete game/event.
     */
    public function destroy(Game $game)
    {
        $game->delete();

        return redirect()
            ->route('admin.sports.games.index')
            ->with('success', 'Game/Event deleted successfully.');
    }
}