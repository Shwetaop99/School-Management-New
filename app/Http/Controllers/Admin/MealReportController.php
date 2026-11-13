<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockLog;
use App\Models\Meal\MealStockTransaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MealReportExport;

class MealReportController extends Controller
{
    /**
     * Meal Reports landing page and individual reports.
     */
    public function index(Request $request)
    {
        $type = $request->query('type');

        // Show the three report options when no report is selected.
        if (!in_array($type, ['stock_in', 'stock_out', 'logs'], true)) {
            return view('allReports.mealReport.index');
        }

        $items = MealItem::query()
            ->orderBy('item_name')
            ->get();

        $transactions = collect();
        $logs = collect();

        if ($type === 'logs') {
            $query = MealStockLog::with([
                'mealItem',
                'stockTransaction',
            ])->orderByDesc('id');

            if ($request->filled('meal_item_id')) {
                $query->where('meal_item_id', $request->meal_item_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $request->from_date
                );
            }

            if ($request->filled('to_date')) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $request->to_date
                );
            }

            $logs = $query->get();
        } else {
            $query = MealStockTransaction::with('mealItem')
                ->where('transaction_type', $type)
                ->orderByDesc('transaction_date')
                ->orderByDesc('id');

            if ($request->filled('meal_item_id')) {
                $query->where('meal_item_id', $request->meal_item_id);
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
        }

        $totalQuantity = $type === 'logs'
            ? $logs->sum(fn ($log) => (float) $log->quantity)
            : $transactions->sum(fn ($transaction) => (float) $transaction->quantity);

        $totalAmount = $transactions->sum(
            fn ($transaction) => (float) ($transaction->total_amount ?? 0)
        );

        $reportTitles = [
            'stock_in' => 'Meal Stock In Report',
            'stock_out' => 'Meal Stock Out Report',
            'logs' => 'Meal Logs Report',
        ];

        return view('allReports.mealReport.detail', [
            'type' => $type,
            'reportTitle' => $reportTitles[$type],
            'items' => $items,
            'transactions' => $transactions,
            'logs' => $logs,
            'totalRecords' => $type === 'logs'
                ? $logs->count()
                : $transactions->count(),
            'totalQuantity' => $totalQuantity,
            'totalAmount' => $totalAmount,
        ]);
    }

    /**
     * Download the selected report as PDF.
     */
    public function pdf(Request $request)
    {
        $type = $request->query('type');

        if (!in_array($type, ['stock_in', 'stock_out', 'logs'], true)) {
            return redirect()
                ->route('admin.reports.meal')
                ->with('error', 'Please select a meal report first.');
        }

        $data = $this->getReportData($request, $type);

        $pdf = Pdf::loadView(
            'allReports.mealReport.pdf',
            $data
        )->setPaper('a4', 'landscape');

        return $pdf->download(
            'meal-' . str_replace('_', '-', $type) . '-report.pdf'
        );
    }

    /**
     * Keep the existing Excel download route working.
     */
    public function excel(Request $request)
    {
        return Excel::download(
            new MealReportExport($request->query()),
            'meal-report.xlsx'
        );
    }

    /**
     * Build data for the selected PDF report.
     */
    private function getReportData(Request $request, string $type): array
    {
        $items = MealItem::query()
            ->orderBy('item_name')
            ->get();

        $transactions = collect();
        $logs = collect();

        if ($type === 'logs') {
            $query = MealStockLog::with([
                'mealItem',
                'stockTransaction',
            ])->orderByDesc('id');

            if ($request->filled('meal_item_id')) {
                $query->where('meal_item_id', $request->meal_item_id);
            }

            if ($request->filled('from_date')) {
                $query->whereDate(
                    'created_at',
                    '>=',
                    $request->from_date
                );
            }

            if ($request->filled('to_date')) {
                $query->whereDate(
                    'created_at',
                    '<=',
                    $request->to_date
                );
            }

            $logs = $query->get();
        } else {
            $query = MealStockTransaction::with('mealItem')
                ->where('transaction_type', $type)
                ->orderByDesc('transaction_date')
                ->orderByDesc('id');

            if ($request->filled('meal_item_id')) {
                $query->where('meal_item_id', $request->meal_item_id);
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
        }

        $reportTitles = [
            'stock_in' => 'Meal Stock In Report',
            'stock_out' => 'Meal Stock Out Report',
            'logs' => 'Meal Logs Report',
        ];

        return [
            'type' => $type,
            'reportTitle' => $reportTitles[$type],
            'items' => $items,
            'transactions' => $transactions,
            'logs' => $logs,
            'totalRecords' => $type === 'logs'
                ? $logs->count()
                : $transactions->count(),
            'totalQuantity' => $type === 'logs'
                ? $logs->sum(fn ($log) => (float) $log->quantity)
                : $transactions->sum(fn ($transaction) => (float) $transaction->quantity),
            'totalAmount' => $transactions->sum(
                fn ($transaction) => (float) ($transaction->total_amount ?? 0)
            ),
        ];
    }
}
