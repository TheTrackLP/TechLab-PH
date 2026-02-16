<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Supplier;
use Illuminate\Http\Request;
use DB;

class AdminController extends Controller
{
    public function AdminDashboard(){
        $total_products = Products::count();
        $inactive_products = Products::where('is_active', 0)->count();
        // $low_stocks = Products::whereColumn('minimum_stock', '>', 'stock_quantity')->count();
        $list_low_stocks = Products::select(
            "products.*",
                     "categories.category_name",
        )
        ->join('categories', 'categories.id','=', 'products.category_id')
        ->whereColumn('minimum_stock', '>', 'stock_quantity')->get();
        $low_stocks = Products::whereColumn('minimum_stock', '>', 'stock_quantity')->count();
        $out_of_stocks = Products::where('stock_quantity', '=', 0)->count();
        $suppliers = Supplier::count();
        $inactive_suppliers = Supplier::where('is_active', 0)->count();
        return view('admin.dashboard', compact('total_products', 'inactive_products', 'low_stocks', 'out_of_stocks', 'suppliers', 'inactive_suppliers', 'list_low_stocks'));
    }
}