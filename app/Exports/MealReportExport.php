<?php

namespace App\Exports;

use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockTransaction;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MealReportExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = MealStockTransaction::with('mealItem')
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');

        if (!empty($this->filters['meal_item_id'])) {
            $query->where(
                'meal_item_id',
                $this->filters['meal_item_id']
            );
        }

        if (!empty($this->filters['transaction_type'])) {
            $query->where(
                'transaction_type',
                $this->filters['transaction_type']
            );
        }

        if (!empty($this->filters['from_date'])) {
            $query->whereDate(
                'transaction_date',
                '>=',
                $this->filters['from_date']
            );
        }

        if (!empty($this->filters['to_date'])) {
            $query->whereDate(
                'transaction_date',
                '<=',
                $this->filters['to_date']
            );
        }

        return $query->get()->map(function ($transaction) {
            return [
                'Item' => $transaction->mealItem?->item_name ?? '-',
                'Transaction Type' => $transaction->transaction_type,
                'Quantity' => $transaction->quantity,
                'Unit' => $transaction->unit,
                'Rate' => $transaction->rate,
                'Total Amount' => $transaction->total_amount,
                'Transaction Date' => optional(
                    $transaction->transaction_date
                )->format('d-m-Y'),
                'Supplier' => $transaction->supplier ?? '-',
                'Reason' => $transaction->reason ?? '-',
                'Remarks' => $transaction->remarks ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Item',
            'Transaction Type',
            'Quantity',
            'Unit',
            'Rate',
            'Total Amount',
            'Transaction Date',
            'Supplier',
            'Reason',
            'Remarks',
        ];
    }
}