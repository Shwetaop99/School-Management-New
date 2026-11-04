<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sports\Games\Game;
use App\Models\Sports\Achievements\Achievement;
use App\Models\Sports\Equipment\Equipment;
use Illuminate\Http\Request;

class SportsReportController extends Controller
{
    /**
     * Shared summary information for every Sports Report screen.
     */
    private function summaryData(): array
    {
        return [
            'totalGames' => Game::count(),
            'totalAchievements' => Achievement::count(),
            'totalEquipment' => Equipment::count(),
            'totalEquipmentUnits' => (int) Equipment::sum('quantity'),
            'availableEquipmentUnits' => (int) Equipment::sum('available_quantity'),
        ];
    }

    /**
     * Main Sports Reports dashboard.
     */
    public function index()
    {
        return view('allReports.sports.index', array_merge(
            $this->summaryData(),
            ['reportType' => 'overview']
        ));
    }

    /**
     * Game and Event report.
     */
    public function games(Request $request)
    {
        $query = Game::query();

        $search = trim((string) $request->input('search', ''));
        $sportName = $request->input('sport_name', '');
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('sport_name', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%")
                    ->orWhere('venue', 'like', "%{$search}%")
                    ->orWhere('organizer', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        if ($sportName !== '') {
            $query->where('sport_name', $sportName);
        }

        if ($fromDate !== '') {
            $query->whereDate('event_date', '>=', $fromDate);
        }

        if ($toDate !== '') {
            $query->whereDate('event_date', '<=', $toDate);
        }

        $games = $query->orderByDesc('event_date')->get();

        $sports = Game::query()
            ->whereNotNull('sport_name')
            ->where('sport_name', '!=', '')
            ->distinct()
            ->orderBy('sport_name')
            ->pluck('sport_name');

        return view('allReports.sports.index', array_merge(
            $this->summaryData(),
            compact('games', 'sports', 'search', 'sportName', 'fromDate', 'toDate'),
            ['reportType' => 'games']
        ));
    }

    /**
     * Student Achievement report.
     */
    public function achievements(Request $request)
    {
        $query = Achievement::query();

        $search = trim((string) $request->input('search', ''));
        $sportName = $request->input('sport_name', '');
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('student_name', 'like', "%{$search}%")
                    ->orWhere('sport_name', 'like', "%{$search}%")
                    ->orWhere('competition_name', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('academic_year', 'like', "%{$search}%");
            });
        }

        if ($sportName !== '') {
            $query->where('sport_name', $sportName);
        }

        if ($fromDate !== '') {
            $query->whereDate('achievement_date', '>=', $fromDate);
        }

        if ($toDate !== '') {
            $query->whereDate('achievement_date', '<=', $toDate);
        }

        $achievements = $query->orderByDesc('achievement_date')->get();

        $sports = Achievement::query()
            ->whereNotNull('sport_name')
            ->where('sport_name', '!=', '')
            ->distinct()
            ->orderBy('sport_name')
            ->pluck('sport_name');

        return view('allReports.sports.index', array_merge(
            $this->summaryData(),
            compact(
                'achievements',
                'sports',
                'search',
                'sportName',
                'fromDate',
                'toDate'
            ),
            ['reportType' => 'achievements']
        ));
    }

    /**
     * Sports Equipment Inventory report.
     */
    public function equipment(Request $request)
    {
        $query = Equipment::query();

        $search = trim((string) $request->input('search', ''));
        $fromDate = $request->input('from_date', '');
        $toDate = $request->input('to_date', '');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('equipment_name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%")
                    ->orWhere('supplier', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('condition', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if ($fromDate !== '') {
            $query->whereDate('purchase_date', '>=', $fromDate);
        }

        if ($toDate !== '') {
            $query->whereDate('purchase_date', '<=', $toDate);
        }

        $equipment = $query->orderBy('equipment_name')->get();

        return view('allReports.sports.index', array_merge(
            $this->summaryData(),
            compact('equipment', 'search', 'fromDate', 'toDate'),
            ['reportType' => 'equipment']
        ));
    }
}
