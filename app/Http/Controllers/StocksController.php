<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\RestockItems;
use App\Models\Restocks;
use App\Models\StockMovements;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StocksController extends Controller
{
    public function RestockIndex(){
        return inertia('Admin/Backend/Stocks', [
            'suppliers'=>Suppliers::all(),
            'products'=>Products::all(),
        ]);
    }

    public function RestockStore(Request $request){
        DB::beginTransaction();
        
        try {
            $restockProducts = $request->restockProducts;
            $supplier_id = $request->supplier_id;
            $referenceNo = $request->referenceNo;
            $totalItemsRestock = $request->totalItemsRestock;
            $totalAmountRestock = $request->totalAmountRestock;

            $restocks = Restocks::create([
                'supplier_id' => $supplier_id,
                'reference_no' => null,
                'total_items' => $totalItemsRestock,
                'total_amount' => 0,
                'notes' => $request->notes,
                'created_by' => null,
                'status' => 'completed',
            ]);

            foreach ($restockProducts as $stocks) {
                $product = Products::lockForUpdate()->findOrFail($stocks['product_id']);

                $subtotal = $stocks['productNewQTY'] * $stocks['productNewCost'];

                RestockItems::create([
                    'restock_id' => $restocks->id,
                    'product_id' => $product->id,
                    'quantity' => $stocks['productNewQTY'],
                    'cost_price_snapshot' => $product->cost_price,
                    'subtotal' => $subtotal,
                ]);

                $addStock = $product->stock_quantity + $stocks['productNewQTY'];
                $newCostPrice = $stocks['productNewCost'];

                StockMovements::create([
                    'product_id' => $product->id,
                    'type' => 'stocks',
                    'quantity' => +$stocks['quantity'],
                    'reference_id' => $restocks->id,
                    'notes' => null,
                    'created_by' => null,
                ]);

                $product->where('id', $product->id)
                     ->update([
                        'stock_quantity' => $addStock,
                        'cost_price' => $stocks['productNewCost'],
                     ]);
            }

            $year = now()->year;

            $lastRestock = Restocks::whereYear('created_at', $year)
                                    ->whereNotNull('reference_no')
                                    ->orderBy('id', 'desc')
                                    ->first();

            $nextNumber = $lastRestock ? intval(substr($lastRestock->reference_no, -5)) + 1: 1;

            $reference = 'RS-' .$year. '-'.str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $restocks->update([
                'reference_no' => $reference,
                'total_amount' => $newCostPrice,
            ]);

            DB::commit();

            return redirect()->route('stocks.index');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('stocks.index')->with(
                'error', 'Error, Try Again!',
            );
        }
    }
}
