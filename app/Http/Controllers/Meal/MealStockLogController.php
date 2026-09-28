<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockLog;
use Illuminate\Http\Request;

class MealStockLogController extends Controller
{
    /**
     * Display month-wise meal stock logs.
     */
    public function index(Request $request)
    {
        $query = MealStockLog::query()
            ->with([
                'mealItem',
                'stockTransaction',
            ]);

        /*
         * Month filter.
         *
         * Example:
         * 2026-09
         */
        if ($request->filled('month')) {
            $month = $request->month;

            if (preg_match('/^\d{4}-\d{2}$/', $month)) {
                $query->whereHas(
                    'stockTransaction',
                    function ($transactionQuery) use ($month) {
                        $transactionQuery->whereRaw(
                            "DATE_FORMAT(transaction_date, '%Y-%m') = ?",
                            [$month]
                        );
                    }
                );
            }
        }

        /*
         * Search.
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'reason',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'remarks',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'mealItem',
                        function ($itemQuery) use ($search) {
                            $itemQuery->where(
                                'item_name',
                                'like',
                                "%{$search}%"
                            );
                        }
                    )
                    ->orWhereHas(
                        'stockTransaction',
                        function ($transactionQuery) use ($search) {
                            $transactionQuery->where(
                                'supplier',
                                'like',
                                "%{$search}%"
                            );
                        }
                    );
            });
        }

        /*
         * Filter by meal item.
         */
        if ($request->filled('meal_item_id')) {
            $query->where(
                'meal_item_id',
                $request->meal_item_id
            );
        }

        /*
         * Filter by category.
         */
        if ($request->filled('category')) {
            $query->whereHas(
                'mealItem',
                function ($itemQuery) use ($request) {
                    $itemQuery->where(
                        'category',
                        $request->category
                    );
                }
            );
        }

        /*
         * Filter by Stock In / Stock Out.
         */
        if ($request->filled('transaction_type')) {
            $query->where(
                'action',
                $request->transaction_type
            );
        }

        /*
         * Date From.
         */
        if ($request->filled('date_from')) {
            $query->whereHas(
                'stockTransaction',
                function ($transactionQuery) use ($request) {
                    $transactionQuery->whereDate(
                        'transaction_date',
                        '>=',
                        $request->date_from
                    );
                }
            );
        }

        /*
         * Date To.
         */
        if ($request->filled('date_to')) {
            $query->whereHas(
                'stockTransaction',
                function ($transactionQuery) use ($request) {
                    $transactionQuery->whereDate(
                        'transaction_date',
                        '<=',
                        $request->date_to
                    );
                }
            );
        }

        /*
         * Summary totals.
         *
         * Clone before pagination so the
         * summary uses all filtered records.
         */
        $summaryQuery = clone $query;

        $totalMovements = (clone $summaryQuery)
            ->count();

        $totalStockIn = (clone $summaryQuery)
            ->where('action', 'stock_in')
            ->sum('quantity');

        $totalStockOut = (clone $summaryQuery)
            ->where('action', 'stock_out')
            ->sum('quantity');

        /*
         * Logs.
         */
        $logs = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
         * Active meal items for filter.
         */
        $items = MealItem::query()
            ->where('status', 'active')
            ->orderBy('item_name')
            ->get();

        /*
         * Available categories.
         */
        $categories = MealItem::query()
            ->where('status', 'active')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        /*
         * Number of active low-stock items.
         */
        $lowStockItems = MealItem::query()
            ->where('status', 'active')
            ->whereColumn(
                'current_stock',
                '<=',
                'minimum_stock'
            )
            ->count();

        return view(
            'admin.meal.logs.index',
            compact(
                'logs',
                'items',
                'categories',
                'totalMovements',
                'totalStockIn',
                'totalStockOut',
                'lowStockItems'
            )
        );
    }

    /**
     * Display detailed information for one log.
     */
    public function show(MealStockLog $mealStockLog)
    {
        $mealStockLog->load([
            'mealItem',
            'stockTransaction',
        ]);

        return view(
            'admin.meal.logs.show',
            compact('mealStockLog')
        );
    }
}