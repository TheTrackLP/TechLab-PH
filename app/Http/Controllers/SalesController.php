<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function SalesIndex(){
        $products = Products::where('is_active', 1)->get();
        return view('backend.sales', compact('products'));
    }

    public function getProductData($id){
        $product = Products::select(
                            "products.*",
                            "categories.category_name",
                             )
                             ->join('categories', 'categories.id', '=', 'products.category_id')
                             ->where('products.id', $id)
                             ->firstOrFail();   
        return response()->json([
            'data'=>$product,
        ]);
    }

    public function SaleCompleted(Request $request){
        
    }
}