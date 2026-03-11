<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\ReturnItems;
use App\Models\Returns;
use App\Models\Sales;
use App\Models\salesItem;
use App\Models\StockMovements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ReturnController extends Controller
{
    public function ReturnIdex(){
        return view('backend.returns');
    }

    public function GetInvoiceNo($invoice_no){
        $sale = Sales::where('invoice_no', $invoice_no)->first();
        $saleItems = salesItem::select(
            "sales_items.*",
            "products.name",
        )
        ->join('sales', 'sales.id', '=', 'sales_items.sale_id')
        ->join('products', 'products.id', '=', 'sales_items.product_id')
        ->where('sales_items.sale_id', $sale->id)
        ->get();
        return response()->json([
            'sale'=>$sale,
            'items'=>$saleItems,
        ]);
    }

    public function StoreReturnItems(Request $request){
        DB::beginTransaction();
        try{
            $returnItems = $request->returnItems;
            $totalReturnAmount = $request->totalReturnAmount;
            $reason = $request->reason;
            $notes = $request->notes;
            $sale_id = $request->sale_id;
            $reason_type = $request->reason_type;

            $return = Returns::create([
                'sale_id' => $sale_id,
                'return_no' => null,
                'return_type' => $reason_type,
                'total_amount' => $totalReturnAmount,
                'reason' => $reason,
                'notes' => $notes,
                'created_by' => null,
            ]);

            foreach ($returnItems as $item) {
                $product_id = Products::lockForUpdate()->findOrFail($item['product_id']);
                $subTotal = $item['price'] * $item['quantity'];

                ReturnItems::create([
                    'return_id'=>$return->id,
                    'product_id'=>$item['product_id'],
                    'quantity'=>$item['quantity'],
                    'selling_price_snapshot' => $item['price'],
                    'subtotal'=>$subTotal,
                ]);

                $product_id->stock_quantity += $item['quantity'];
                $product_id->save();


                StockMovements::create(attributes: [
                    'product_id' => $product_id->id,
                    'type' => 'return',
                    'quantity' => +$item['quantity'],
                    'reference_id' => $return->id,
                    'notes' => null,
                    'created_by' => null,
                ]);

            }

            $year = now()->year;

            $lastReturn = Returns::whereYear('created_at', $year)
                                ->whereNotNull('return_no')
                                ->orderBy('id','desc')
                                ->first();
            
            $nextnNumber = $lastReturn ? intval(substr($lastReturn->return_no, -5)) + 1: 1;

            $return_no = "RT-" . $year . "-". str_pad($nextnNumber, 5, '0', STR_PAD_LEFT);

            $return->update([
                'return_no' => $return_no,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Return Item/s completed successfully.',
                'return_no' => $return_no,
            ]);

        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message'=>$e->getMessage()
            ], 400);
        }
    }
}