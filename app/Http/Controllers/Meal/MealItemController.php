<?php

namespace App\Http\Controllers\Meal;

use App\Http\Controllers\Controller;
use App\Models\Meal\MealItem;
use App\Models\Meal\MealStockLog;
use App\Models\Meal\MealStockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MealItemController extends Controller
{
    /**
     * Display meal items.
     */
    public function index(Request $request)
    {
        $query = MealItem::query();

        // Search by item name or category.
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'item_name',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'category',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        // Filter by status.
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        // Meal items.
        $items = $query
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        // Recent Stock In / Stock Out movements.
        $transactions = MealStockTransaction::with('mealItem')
            ->latest('transaction_date')
            ->latest('id')
            ->paginate(
                10,
                ['*'],
                'transaction_page'
            )
            ->withQueryString();

        return view(
            'admin.meal.items.index',
            compact(
                'items',
                'transactions'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view(
            'admin.meal.items.create'
        );
    }

    /**
     * Store a new meal item.
     *
     * Opening Stock is automatically recorded
     * as a Stock In transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => [
                'required',
                'string',
                'max:255',
                'unique:meal_items,item_name',
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            'opening_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            /*
             * Opening stock entered while creating
             * the item is treated as Stock In.
             */
            $openingStock = (float) $validated['opening_stock'];

            /*
             * Create meal item with opening stock
             * as its initial current stock.
             */
            $mealItem = MealItem::create([
                'item_name' => $validated['item_name'],

                'category' => $validated['category'] ?? null,

                'current_stock' => $openingStock,

                'unit' => $validated['unit'],

                'minimum_stock' => $validated['minimum_stock'],

                'status' => 'active',

                'description' => $validated['description'] ?? null,
            ]);

            /*
             * Only create a Stock In transaction
             * when opening stock is greater than zero.
             */
            if ($openingStock > 0) {

                $transaction = MealStockTransaction::create([
                    'meal_item_id' => $mealItem->id,

                    'transaction_type' => 'stock_in',

                    'quantity' => $openingStock,

                    'unit' => $mealItem->unit,

                    'rate' => null,

                    'total_amount' => null,

                    'transaction_date' => now()->toDateString(),

                    'supplier' => null,

                    'reason' => 'Opening Stock',

                    'remarks' => 'Initial stock added while creating meal item.',

                    'created_by' => auth()->id(),
                ]);

                /*
                 * Create stock log for the opening stock.
                 */
                MealStockLog::create([
                    'meal_item_id' => $mealItem->id,

                    'stock_transaction_id' => $transaction->id,

                    'action' => 'stock_in',

                    'quantity' => $openingStock,

                    'unit' => $mealItem->unit,

                    'previous_stock' => 0,

                    'updated_stock' => $openingStock,

                    'performed_by' => auth()->id(),

                    'reason' => 'Opening Stock',

                    'remarks' => 'Initial stock added while creating meal item.',
                ]);
            }
        });

        return redirect()
            ->route('admin.meal.items.index')
            ->with(
                'success',
                'Meal item added successfully with opening stock recorded as Stock In.'
            );
    }

    /**
     * Show edit form.
     */
    public function edit(MealItem $mealItem)
    {
        return view(
            'admin.meal.items.edit',
            compact('mealItem')
        );
    }

    /**
     * Update meal item.
     */
    public function update(
        Request $request,
        MealItem $mealItem
    ) {
        $validated = $request->validate([
            'item_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(
                    'meal_items',
                    'item_name'
                )->ignore($mealItem->id),
            ],

            'category' => [
                'nullable',
                'string',
                'max:100',
            ],

            'unit' => [
                'required',
                'string',
                'max:50',
            ],

            /*
             * Current stock is now editable from
             * the Meal Item edit page.
             */
            'current_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'minimum_stock' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

        $mealItem->update($validated);

        return redirect()
            ->route('admin.meal.items.index')
            ->with(
                'success',
                'Meal item updated successfully.'
            );
    }

    /**
     * Delete meal item.
     */
    public function destroy(MealItem $mealItem)
    {
        /*
         * Do not allow deletion when stock
         * transactions exist.
         */
        if ($mealItem->stockTransactions()->exists()) {
            return redirect()
                ->route('admin.meal.items.index')
                ->with(
                    'error',
                    'This meal item cannot be deleted because stock transactions exist for it.'
                );
        }

        $mealItem->delete();

        return redirect()
            ->route('admin.meal.items.index')
            ->with(
                'success',
                'Meal item deleted successfully.'
            );
    }
}