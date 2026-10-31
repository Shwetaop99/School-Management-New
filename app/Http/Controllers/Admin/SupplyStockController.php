<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyItem;
use App\Models\SupplyStock;
use App\Models\SupplyStockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\SchoolSetting;

class SupplyStockController extends Controller
{
    /**
     * Display stock list.
     */
    /**
 * Display stock list.
 */
public function index()
{
    $academicYear = request('academic_year', '2026-27');

    // Get all active supply items.
    $supplyItems = SupplyItem::where('status', true)
        ->orderBy('item_name')
        ->get();

    // Automatically create missing stock records for this academic year.
    foreach ($supplyItems as $item) {
        SupplyStock::firstOrCreate(
            [
                'supply_item_id' => $item->id,
                'academic_year' => $academicYear,
            ],
            [
                'quantity' => 0,
                'minimum_quantity' => 0,
                'status' => true,
            ]
        );
    }

    // Load all stock records with their related supply items.
    $stocks = SupplyStock::with('supplyItem')
        ->where('academic_year', $academicYear)
        ->whereHas('supplyItem', function ($query) {
            $query->where('status', true);
        })
        ->orderBy('id', 'desc')
        ->get();

    // Dashboard statistics.
    $totalItems = $stocks->count();

    $totalQuantity = $stocks->sum('quantity');

    $lowStockCount = $stocks->filter(function ($stock) {
        return $stock->status &&
               $stock->quantity <= $stock->minimum_quantity;
    })->count();

    $activeStockCount = $stocks->where('status', true)->count();

    return view('admin.supply-stocks.index', compact(
        'stocks',
        'supplyItems',
        'academicYear',
        'totalItems',
        'totalQuantity',
        'lowStockCount',
        'activeStockCount'
    ));
}

    /**
     * Receive new stock.
     */
    public function receive(Request $request)
    {
        $validated = $request->validate([
            'supply_item_id' => [
                'required',
                'integer',
                Rule::exists('supply_items', 'id'),
            ],
            'academic_year' => ['required', 'string', 'max:20'],
            'quantity' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated) {
            $stock = SupplyStock::firstOrCreate(
                [
                    'supply_item_id' => $validated['supply_item_id'],
                    'academic_year' => $validated['academic_year'],
                ],
                [
                    'quantity' => 0,
                    'minimum_quantity' => 0,
                    'status' => true,
                ]
            );

            // Lock the existing stock row before changing its balance.
            $stock = SupplyStock::whereKey($stock->id)
                ->lockForUpdate()
                ->firstOrFail();

            $stock->quantity += (int) $validated['quantity'];
            $stock->save();

            SupplyStockTransaction::create([
                'supply_item_id' => $validated['supply_item_id'],
                'academic_year' => $validated['academic_year'],
                'transaction_type' => 'receive',
                'quantity' => (int) $validated['quantity'],
                'balance_quantity' => $stock->quantity,
                'reference_type' => 'stock_receive',
                'reference_id' => null,
                'remarks' => $validated['remarks'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.supply-stocks.index', [
                'academic_year' => $validated['academic_year'],
            ])
            ->with('success', 'Stock received successfully.');
    }

    /**
     * Adjust stock quantity.
     * Positive quantity increases stock.
     * Negative quantity decreases stock.
     */
    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'supply_item_id' => [
                'required',
                'integer',
                Rule::exists('supply_items', 'id'),
            ],
            'academic_year' => ['required', 'string', 'max:20'],
            'quantity' => ['required', 'integer', 'not_in:0'],
            'remarks' => ['required', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated) {
            $stock = SupplyStock::firstOrCreate(
                [
                    'supply_item_id' => $validated['supply_item_id'],
                    'academic_year' => $validated['academic_year'],
                ],
                [
                    'quantity' => 0,
                    'minimum_quantity' => 0,
                    'status' => true,
                ]
            );

            $stock = SupplyStock::whereKey($stock->id)
                ->lockForUpdate()
                ->firstOrFail();

            $adjustment = (int) $validated['quantity'];
            $newQuantity = $stock->quantity + $adjustment;

            if ($newQuantity < 0) {
                throw ValidationException::withMessages([
                    'quantity' => 'The adjustment cannot reduce stock below zero. Current available stock: ' . $stock->quantity,
                ]);
            }

            $stock->quantity = $newQuantity;
            $stock->save();

            SupplyStockTransaction::create([
                'supply_item_id' => $validated['supply_item_id'],
                'academic_year' => $validated['academic_year'],
                'transaction_type' => 'adjustment',

                // Keep the sign so the history shows increase or decrease.
                'quantity' => $adjustment,

                'balance_quantity' => $stock->quantity,
                'reference_type' => 'stock_adjustment',
                'reference_id' => null,
                'remarks' => $validated['remarks'],
                'created_by' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('admin.supply-stocks.index', [
                'academic_year' => $validated['academic_year'],
            ])
            ->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Display transaction history for an item.
     */
    public function history($supplyItemId)
    {
        $academicYear = request('academic_year', '2026-27');

        $item = SupplyItem::findOrFail($supplyItemId);

        $transactions = SupplyStockTransaction::with('creator')
            ->where('supply_item_id', $supplyItemId)
            ->where('academic_year', $academicYear)
            ->latest('id')
            ->get();

        return view('admin.supply-stocks.history', compact(
            'item',
            'transactions',
            'academicYear'
        ));
    }

   public function print()
{
    $academicYear = request('academic_year', '2026-27');

    $stocks = SupplyStock::with('supplyItem')
        ->where('academic_year', $academicYear)
        ->orderBy('id', 'desc')
        ->get();

    $schoolSetting = \App\Models\SchoolSetting::first();

    return view('admin.supply-stocks.print', compact(
        'stocks',
        'academicYear',
        'schoolSetting'
    ));
}

}