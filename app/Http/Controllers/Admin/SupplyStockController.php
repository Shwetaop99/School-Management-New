<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyItem;
use App\Models\SupplyStock;
use App\Models\SupplyStockTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SupplyStockController extends Controller
{
    /**
     * Stock list
     */
    public function index()
    {
        $academicYear = request('academic_year', '2026-27');

        $stocks = SupplyStock::with('supplyItem')
            ->where('academic_year', $academicYear)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.supply-stocks.index', compact(
            'stocks',
            'academicYear'
        ));
    }

    /**
     * Receive stock
     */
    public function receive(Request $request)
    {
        $validated = $request->validate([
            'supply_item_id' => [
                'required',
                'integer',
                Rule::exists('supply_items', 'id'),
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'remarks' => [
                'nullable',
                'string',
                'max:1000',
            ],
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

            $stock->quantity += $validated['quantity'];
            $stock->save();

            SupplyStockTransaction::create([
                'supply_item_id' => $validated['supply_item_id'],
                'academic_year' => $validated['academic_year'],
                'transaction_type' => 'receive',
                'quantity' => $validated['quantity'],
                'balance_quantity' => $stock->quantity,
                'reference_type' => 'stock_receive',
                'reference_id' => null,
                'remarks' => $validated['remarks'] ?? null,
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Stock received successfully.'
        );
    }

    /**
     * Adjust stock
     */
    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'supply_item_id' => [
                'required',
                'integer',
                Rule::exists('supply_items', 'id'),
            ],

            'academic_year' => [
                'required',
                'string',
                'max:20',
            ],

            'quantity' => [
                'required',
                'integer',
                'not_in:0',
            ],

            'remarks' => [
                'required',
                'string',
                'max:1000',
            ],
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

            $newQuantity = $stock->quantity + $validated['quantity'];

            if ($newQuantity < 0) {
                abort(
                    422,
                    'Stock quantity cannot become negative.'
                );
            }

            $stock->quantity = $newQuantity;
            $stock->save();

            SupplyStockTransaction::create([
                'supply_item_id' => $validated['supply_item_id'],
                'academic_year' => $validated['academic_year'],
                'transaction_type' => 'adjustment',
                'quantity' => abs($validated['quantity']),
                'balance_quantity' => $stock->quantity,
                'reference_type' => 'stock_adjustment',
                'reference_id' => null,
                'remarks' => $validated['remarks'],
                'created_by' => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Stock adjusted successfully.'
        );
    }

    /**
     * Stock transaction history
     */
    public function history($supplyItemId)
    {
        $academicYear = request('academic_year', '2026-27');

        $item = SupplyItem::findOrFail($supplyItemId);

        $transactions = SupplyStockTransaction::where(
                'supply_item_id',
                $supplyItemId
            )
            ->where('academic_year', $academicYear)
            ->latest('id')
            ->get();

        return view(
            'admin.supply-stocks.history',
            compact(
                'item',
                'transactions',
                'academicYear'
            )
        );
    }
}