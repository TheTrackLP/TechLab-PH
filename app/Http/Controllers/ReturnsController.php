<?php

namespace App\Http\Controllers;

use App\Models\SaleItems;
use App\Models\Sales;
use Illuminate\Http\Request;

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
        dd($request->all());
    }
}
