<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class MealStockLogController extends Controller
{
    /**
     * Display month-wise meal stock logs.
     */
    public function index(Request $request)
    {
        $query = $this->filteredQuery($request);

        /*
        |--------------------------------------------------------------------------
        | Summary totals
        |--------------------------------------------------------------------------
        */

        $summaryQuery = clone $query;

        $totalMovements = (clone $summaryQuery)->count();

        $totalStockIn = (clone $summaryQuery)
            ->where('action', 'stock_in')
            ->sum('quantity');

        $totalStockOut = (clone $summaryQuery)
            ->where('action', 'stock_out')
            ->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | Logs
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Active meal items for filter
        |--------------------------------------------------------------------------
        */

        $items = MealItem::query()
            ->where('status', 'active')
            ->orderBy('item_name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Available categories
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Number of active low-stock items
        |--------------------------------------------------------------------------
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

    /**
     * Download filtered meal stock logs as PDF.
     */
    public function downloadPdf(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latest('id')
            ->get();

        $totalMovements = $logs->count();

        $totalStockIn = $logs
            ->where('action', 'stock_in')
            ->sum('quantity');

        $totalStockOut = $logs
            ->where('action', 'stock_out')
            ->sum('quantity');

        $pdf = Pdf::loadView(
            'admin.meal.logs.pdf',
            [
                'logs' => $logs,
                'totalMovements' => $totalMovements,
                'totalStockIn' => $totalStockIn,
                'totalStockOut' => $totalStockOut,
            ]
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'meal-stock-logs-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    /**
     * Download filtered meal stock logs as Excel.
     */
    public function downloadExcel(Request $request)
    {
        $logs = $this->filteredQuery($request)
            ->latest('id')
            ->get();

        return Excel::download(
            new class($logs) implements
                FromCollection,
                WithHeadings,
                ShouldAutoSize
            {
                protected Collection $logs;

                public function __construct(Collection $logs)
                {
                    $this->logs = $logs;
                }

                /**
                 * Excel data.
                 */
                public function collection()
                {
                    return $this->logs->map(function ($log) {
                        return [
                            'Transaction ID' => $log->stock_transaction_id
                                ? 'TRX-' . str_pad(
                                    $log->stock_transaction_id,
                                    6,
                                    '0',
                                    STR_PAD_LEFT
                                )
                                : '-',

                            'Date' => optional(
                                $log->stockTransaction
                            )->transaction_date
                                ? $log->stockTransaction
                                    ->transaction_date
                                    ->format('d-m-Y')
                                : optional($log->created_at)
                                    ->format('d-m-Y'),

                            'Time' => optional($log->created_at)
                                ->format('h:i A'),

                            'Item' => optional(
                                $log->mealItem
                            )->item_name ?? '-',

                            'Category' => optional(
                                $log->mealItem
                            )->category ?? '-',

                            'Type' => ucfirst(
                                str_replace(
                                    '_',
                                    ' ',
                                    $log->action
                                )
                            ),

                            'Quantity' => number_format(
                                (float) $log->quantity,
                                2
                            ),

                            'Unit' => $log->unit ?? '-',

                            'Stock Before' => number_format(
                                (float) $log->previous_stock,
                                2
                            ),

                            'Stock After' => number_format(
                                (float) $log->updated_stock,
                                2
                            ),

                            'Reason' => $log->reason ?? '-',

                            'Supplier' => optional(
                                $log->stockTransaction
                            )->supplier ?? '-',

                            'Performed By' => $log->performed_by ?? '-',

                            'Remarks' => $log->remarks ?? '-',
                        ];
                    });
                }

                /**
                 * Excel headings.
                 */
                public function headings(): array
                {
                    return [
                        'Transaction ID',
                        'Date',
                        'Time',
                        'Item',
                        'Category',
                        'Type',
                        'Quantity',
                        'Unit',
                        'Stock Before',
                        'Stock After',
                        'Reason',
                        'Supplier',
                        'Performed By',
                        'Remarks',
                    ];
                }
            },
            'meal-stock-logs-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Build the common filtered query.
     *
     * This query is used by:
     * - Logs page
     * - PDF export
     * - Excel export
     */
    private function filteredQuery(Request $request)
    {
        $query = MealStockLog::query()
            ->with([
                'mealItem',
                'stockTransaction',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Month filter
        |--------------------------------------------------------------------------
        |
        | Example: 2026-09
        |
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
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Filter by meal item
        |--------------------------------------------------------------------------
        */

        if ($request->filled('meal_item_id')) {
            $query->where(
                'meal_item_id',
                $request->meal_item_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter by category
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Filter by Stock In / Stock Out
        |--------------------------------------------------------------------------
        */

        if ($request->filled('transaction_type')) {
            $query->where(
                'action',
                $request->transaction_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
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

        return $query;
    }
}
