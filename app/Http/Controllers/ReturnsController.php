<?php

namespace App\Http\Controllers;

use App\Models\ReturnItems;
use App\Models\SaleItems;
use App\Models\Sales;
use App\Models\Returns;
use App\Models\Products;
use App\Models\StockMovements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnsController extends Controller
{
    public function ReturnsIndex(){
        return inertia('Admin/Backend/Returns', [
            'sales'=>Sales::all(),
        ]);
    }

    public function ReturnSaleItems($id){
        $items = SaleItems::select(
            'sale_items.*',
            'products.name as product_name',
        )
        ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
        ->join('products', 'products.id', '=', 'sale_items.product_id')
        ->where('sale_items.sale_id', $id)
        ->get();
        return response()->json($items);
    }

    public function ReturnItemsStore(Request $request){
        DB::BeginTransaction();

        try {
            $returnItems = $request->returnItems;
            $returnReason = $request->returnReason;
            $returnType = $request->returnType;
            $returnNote = $request->returnNote;

            $return = Returns::create([
            'sale_id' => null,
            'return_no' => null,
            'return_type' => $returnType,
            'total_amount' => 0,
            'reason' => $returnReason,
            'notes' => $returnNote,
            'created_by' => null,
            ]);

            $totalAmount = 0;

            foreach ($returnItems as $item) {
                $product_id = Products::lockForUpdate()->findorfail($item['product_id']);
                $subTotal = $item['selling_price_snapshot'] * $item['selectedQTY'];

                ReturnItems::create([
                    'return_id'=>$return->id,
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['selectedQTY'],
                    'selling_price_snapshot' => $item['price'],
                    'subtotal'=>$subTotal,
                ]);

                $product_id->stock_quantity += $item['selectedQTY'];
                $product_id->save();


                StockMovements::create(attributes: [
                    'product_id' => $product_id->id,
                    'type' => 'return',
                    'quantity' => +$item['selectedQTY'],
                    'reference_id' => $return->id,
                    'notes' => null,
                    'created_by' => null,
                ]);

                $year = now()->year;

                $lastReturnTransaction = Returns::whereYear('create_at', $year)
                                                 ->whereNotNull('return_no')
                                                 ->orderBy('id', 'desc')
                                                 ->first();

                $nextnNumber = $lastReturnTransaction ? intval(substr($lastReturnTransaction->return_no, -5)) + 1: 1;

                $return_no = "RT-" . $year . "-". str_pad($nextnNumber, 5, '0', STR_PAD_LEFT);

                $return->update([
                    'return_no' => $return_no,
                ]);

                DB::commit();


                return redirect()->route('return.index')->with(
                    'success', 'Returned Item/s Success',
                );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
                ], 400);
        }
    }
}
