<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\RestockItems;
use App\Models\Restocks;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\StockMovements;
use Illuminate\Support\Facades\DB;

class StocksController extends Controller
{
    public function RestockIndex(){
        $suppliers = Supplier::all();
        $products = Products::all();
        return view('backend.restocks', compact(
            'products',
            'suppliers'
        ));
    }

    public function GetProductData($id){
        $product = Products::findOrFail($id);
        return response()->json([
            'item'=>$product
        ]);
    }

    public function RestockCompleted(Request $request){
        DB::beginTransaction();
        try {
            $stocks = $request->productRestock;
            $totalItems = $request->totalItems;
            $supplier_id = $request->supplier_id;

            $restock = Restocks::create([
                'supplier_id' => $supplier_id,
                'reference_no' => null,
                'total_items' => $totalItems,
                'total_amount' => 0,
                'notes' => $request->notes,
                'created_by' => null,
                'status' => 'completed',
            ]);

            $totalAmount = 0;

            foreach ($stocks as $item) {
                $product = Products::lockForUpdate()->findOrFail($item['product_id']);

                $subTotal = $item['quantity'] * $item['cost_price'];

                RestockItems::create([
                    'restock_id' => $restock->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'cost_price_snapshot' => $product->cost_price,
                    'subtotal' => $subTotal,
                ]);

                $addStock = $product->stock_quantity + $item['quantity'];
                $newCost_Price = $item['cost_price'];

                $product->save();

                $totalAmount += $subTotal;

                StockMovements::create([
                    'product_id' => $product->id,
                    'type' => 'restock',
                    'quantity' => +$item['quantity'],
                    'reference_id' => $restock->id,
                    'notes' => null,
                    'created_by' => null,
                ]);
                $product->where('id', $product->id)
                     ->update([
                        'stock_quantity' => $addStock,
                        'cost_price' => $newCost_Price,
                     ]);
            }

            //Generate Reference No.
            $year = now()->year;

            $lastRestock = Restocks::whereYear('created_at', $year)
                                    ->whereNotNull('reference_no')
                                    ->orderBy('id', 'desc')
                                    ->first();

            $nextNumber = $lastRestock ? intval(substr($lastRestock->reference_no, -5)) + 1 : 1;

            $reference = "RS-". $year . "-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $restock->update([
                'reference_no' => $reference,
                'total_amount' => $totalAmount,
            ]);
                
            DB::commit();

            return response()->json([
                'message' => 'Restock completed successfully.',
                'reference_no' => $reference,
            ]);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
                ], 400);
            }
    }

    public function StockMovementsIndex(){
        $stockMove = StockMovements::select(
            'stock_movements.*',
            'products.name',
            DB::raw("CASE 
                                WHEN stock_movements.type = 'sale' THEN sales.invoice_no 
                                WHEN stock_movements.type = 'restock' THEN restocks.reference_no 
                                ELSE 'Invalid' 
                            END AS reference_no")
        )
        ->leftJoin('products', 'products.id', '=', 'stock_movements.product_id')
        ->leftJoin('sales', 'sales.id', '=', 'stock_movements.reference_id')
        ->leftJoin('restocks', 'restocks.id', '=', 'stock_movements.reference_id')
        ->get();
        return view('backend.stock_movements', compact('stockMove'));
    }

    public function GenerateDateRangeStockMovements(Request $request){
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        $type = $request->type;


        $query = StockMovements::select(
            'stock_movements.*',
            'products.name',
            'products.sku',
            DB::raw("CASE 
                                WHEN stock_movements.type = 'sale' THEN sales.invoice_no 
                                WHEN stock_movements.type = 'restock' THEN restocks.reference_no 
                                ELSE 'N/A' 
                            END AS reference_no")
        )
        ->leftJoin('products', 'products.id', '=', 'stock_movements.product_id')
        ->leftJoin('sales', 'sales.id', '=', 'stock_movements.reference_id')
        ->leftJoin('restocks', 'restocks.id', '=', 'stock_movements.reference_id');

        if ($fromDate && $toDate) {
            $query->whereBetween('stock_movements.created_at', [
                $fromDate . ' 00:00:00',
                $toDate . ' 23:59:59'
            ]);
        }
        
        $StockMovements = $query->orderBy('stock_movements.created_at', 'desc')
                                ->where('stock_movements.type', $type)
                                ->get();
        $totalSale = $StockMovements->where('type', 'sale')->count();
        $totalRestock = $StockMovements->where('type', 'restock')->count();
        $totalAdjustment = $StockMovements->where('type', 'adjustment')->count();
        $totalReturn = $StockMovements->where('type', 'return')->count();
        
        $netMovement = $StockMovements->sum('quantity');        
        return view('backend.print.print_daterange_stockmovements', compact(
            'StockMovements',
            'fromDate',
            'toDate',
            'type',
            'totalSale',
            'totalRestock',
            'totalAdjustment',
            'totalReturn',
            'netMovement',
            ));
    }
}