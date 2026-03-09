<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\salesItem;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function ReturnIdex(){
        return view('backend.returns');
    }

    public function GetInvoiceNo($invoice_no){
        $sale = Sales::where('invoice_no', $invoice_no)->first();
        $saleItems = salesItem::select(
            "sales_items.*",
            "sales.*",
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
}