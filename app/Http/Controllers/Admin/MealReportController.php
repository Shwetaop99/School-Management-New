<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MealReportExport;

class MealReportController extends Controller
{
    /**
     * Meal Management Report
     */
    public function index(Request $request)
    {
        $items = MealItem::query()
            ->orderBy('item_name')
            ->get();

        $query = MealStockTransaction::with('mealItem')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        // Filter by meal item
        if ($request->filled('meal_item_id')) {
            $query->where('meal_item_id', $request->meal_item_id);
        }

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where(
                'transaction_type',
                $request->transaction_type
            );
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $request->to_date
            );
        }

        $transactions = $query->get();

        // Summary
        $totalItems = MealItem::count();

        $lowStockItems = MealItem::whereColumn(
            'current_stock',
            '<=',
            'minimum_stock'
        )->count();

        $totalCurrentStock = MealItem::sum('current_stock');

        $totalStockIn = (clone $query)
            ->where('transaction_type', 'stock_in')
            ->sum('quantity');

        $totalStockOut = (clone $query)
            ->where('transaction_type', 'stock_out')
            ->sum('quantity');

        $totalStockInAmount = (clone $query)
            ->where('transaction_type', 'stock_in')
            ->sum('total_amount');

        return view(
            'allReports.mealReport.index',
            compact(
                'items',
                'transactions',
                'totalItems',
                'lowStockItems',
                'totalCurrentStock',
                'totalStockIn',
                'totalStockOut',
                'totalStockInAmount'
            )
        );
    }

    /**
     * Download Meal Report as PDF
     */
    public function pdf(Request $request)
    {
        $data = $this->getReportData($request);

        $pdf = Pdf::loadView(
            'allReports.mealReport.pdf',
            $data
        );

        return $pdf->download('meal-report.pdf');
    }

    /**
     * Download Meal Report as Excel
     */
    public function excel(Request $request)
    {
        return Excel::download(
            new MealReportExport($request->query()),
            'meal-report.xlsx'
        );
    }

    /**
     * Get report data using the same filters
     */
    private function getReportData(Request $request)
    {
        $items = MealItem::query()
            ->orderBy('item_name')
            ->get();

        $query = MealStockTransaction::with('mealItem')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if ($request->filled('meal_item_id')) {
            $query->where(
                'meal_item_id',
                $request->meal_item_id
            );
        }

        if ($request->filled('transaction_type')) {
            $query->where(
                'transaction_type',
                $request->transaction_type
            );
        }

        if ($request->filled('from_date')) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $request->to_date
            );
        }

        $transactions = $query->get();

        $totalItems = MealItem::count();

        $lowStockItems = MealItem::whereColumn(
            'current_stock',
            '<=',
            'minimum_stock'
        )->count();

        $totalCurrentStock = MealItem::sum('current_stock');

        $totalStockIn = (clone $query)
            ->where('transaction_type', 'stock_in')
            ->sum('quantity');

        $totalStockOut = (clone $query)
            ->where('transaction_type', 'stock_out')
            ->sum('quantity');

        $totalStockInAmount = (clone $query)
            ->where('transaction_type', 'stock_in')
            ->sum('total_amount');

        return compact(
            'items',
            'transactions',
            'totalItems',
            'lowStockItems',
            'totalCurrentStock',
            'totalStockIn',
            'totalStockOut',
            'totalStockInAmount'
        );
    }
}