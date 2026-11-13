<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockLog;
use App\Models\Meal\MealStockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MealStockTransactionController extends Controller
{
    /**
     * Display Stock In / Stock Out records.
     */
    public function index(Request $request)
    {
        $baseQuery = MealStockTransaction::with('mealItem')
            ->latest('transaction_date')
            ->latest('id');

        // Search
        if ($request->filled('search')) {
            $search = trim($request->search);

            $baseQuery->where(function ($query) use ($search) {
                $query->where(
                    'transaction_type',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'supplier',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'reason',
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
                );
            });
        }

        // Date filter
        if ($request->filled('date')) {
            $baseQuery->whereDate(
                'transaction_date',
                $request->date
            );
        }

        // Transaction type filter
        if ($request->filled('transaction_type')) {
            $baseQuery->where(
                'transaction_type',
                $request->transaction_type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stock In
        |--------------------------------------------------------------------------
        */

        $stockIn = (clone $baseQuery)
            ->where('transaction_type', 'stock_in')
            ->paginate(
                10,
                ['*'],
                'stock_in_page'
            )
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Stock Out
        |--------------------------------------------------------------------------
        */

        $stockOut = (clone $baseQuery)
            ->where('transaction_type', 'stock_out')
            ->paginate(
                10,
                ['*'],
                'stock_out_page'
            )
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $totalStockIn = (clone $baseQuery)
            ->where('transaction_type', 'stock_in')
            ->count();

        $totalStockOut = (clone $baseQuery)
            ->where('transaction_type', 'stock_out')
            ->count();

        $stockInQuantity = (clone $baseQuery)
            ->where('transaction_type', 'stock_in')
            ->sum('quantity');

        $stockOutQuantity = (clone $baseQuery)
            ->where('transaction_type', 'stock_out')
            ->sum('quantity');

        return view(
            'admin.meal.items.stock',
            compact(
                'stockIn',
                'stockOut',
                'totalStockIn',
                'totalStockOut',
                'stockInQuantity',
                'stockOutQuantity'
            )
        );
    }

    /**
     * Show Stock In / Stock Out form.
     */
    public function create()
    {
        $mealItems = MealItem::query()
            ->where('status', 'active')
            ->orderBy('item_name')
            ->get();

        return view(
            'admin.meal.items.stock-create',
            compact('mealItems')
        );
    }

    /**
     * Store Stock In / Stock Out transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'meal_item_id' => [
                'required',
                'exists:meal_items,id',
            ],

            'transaction_type' => [
                'required',
                'in:stock_in,stock_out',
            ],

            'quantity' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'rate' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],

            'supplier' => [
                'nullable',
                'string',
                'max:255',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | Lock Item
            |--------------------------------------------------------------------------
            */

            $mealItem = MealItem::query()
                ->lockForUpdate()
                ->findOrFail(
                    $validated['meal_item_id']
                );

            $quantity = (float) $validated['quantity'];

            $previousStock = (float) $mealItem->current_stock;

            /*
            |--------------------------------------------------------------------------
            | Calculate Updated Stock
            |--------------------------------------------------------------------------
            */

            if (
                $validated['transaction_type'] === 'stock_in'
            ) {

                $updatedStock =
                    $previousStock + $quantity;

            } else {

                /*
                |--------------------------------------------------------------------------
                | Stock Out Validation
                |--------------------------------------------------------------------------
                */

                if ($quantity > $previousStock) {
                    abort(
                        422,
                        'Stock Out quantity cannot be greater than available stock.'
                    );
                }

                $updatedStock =
                    $previousStock - $quantity;
            }

            /*
            |--------------------------------------------------------------------------
            | Rate & Total Amount
            |--------------------------------------------------------------------------
            */

            $rate = null;

            if (
                array_key_exists('rate', $validated)
                && $validated['rate'] !== null
                && $validated['rate'] !== ''
            ) {
                $rate = (float) $validated['rate'];
            }

            $totalAmount = null;

            if ($rate !== null) {
                $totalAmount =
                    $quantity * $rate;
            }

            /*
            |--------------------------------------------------------------------------
            | Create Stock Transaction
            |--------------------------------------------------------------------------
            */

            $transaction =
                MealStockTransaction::create([
                    'meal_item_id' =>
                        $mealItem->id,

                    'transaction_type' =>
                        $validated['transaction_type'],

                    'quantity' =>
                        $quantity,

                    'unit' =>
                        $mealItem->unit,

                    'rate' =>
                        $rate,

                    'total_amount' =>
                        $totalAmount,

                    'transaction_date' =>
                        $validated['transaction_date'],

                    'supplier' =>
                        $validated['supplier'] ?? null,

                    'reason' =>
                        $validated['reason'] ?? null,

                    'remarks' =>
                        $validated['remarks'] ?? null,

                    'created_by' =>
                        auth()->id(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Update Current Stock
            |--------------------------------------------------------------------------
            */

            $mealItem->update([
                'current_stock' =>
                    $updatedStock,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Stock Log
            |--------------------------------------------------------------------------
            */

            MealStockLog::create([
                'meal_item_id' =>
                    $mealItem->id,

                'stock_transaction_id' =>
                    $transaction->id,

                'action' =>
                    $validated['transaction_type'],

                'quantity' =>
                    $quantity,

                'unit' =>
                    $mealItem->unit,

                'previous_stock' =>
                    $previousStock,

                'updated_stock' =>
                    $updatedStock,

                'performed_by' =>
                    auth()->id(),

                'reason' =>
                    $validated['reason'] ?? null,

                'remarks' =>
                    $validated['remarks'] ?? null,
            ]);
        });

        return redirect()
            ->route(
                'admin.meal.items.stock.index'
            )
            ->with(
                'success',
                'Stock transaction recorded successfully.'
            );
    }
}