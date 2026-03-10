<?php

namespace App\Http\Controllers;

use App\Models\Returns;
use App\Models\Sales;
use App\Models\salesItem;
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
            $notes = $reason->notes;

            $totalAmount = 0;

            
            Returns::create([
                'sale_id' => null,
                'return_no' => null,
                'return_type' => null,
                'total_amount' => $totalReturnAmount,
                'reason' => $reason,
                'notes' => $notes,
                'created_by' => null,
            ]);

            foreach ($returnItems as $item) {
                $subTotal = $item['price'] * $item['quantity'];

                

            }
        }catch(\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message'=>$e->getMessage()
            ], 400);
        }
    }
}