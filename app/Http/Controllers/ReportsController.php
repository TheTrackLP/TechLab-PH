<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Products;
use App\Models\salesItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function ReportsIndex(){
        $today = now()->toDateString();

        $today_sales = Sales::whereDate('completed_at', $today)
                       ->where('status', 'completed')
                       ->orderBy('completed_at', 'desc')
                       ->get();

        $all_sales = Sales::where('status', 'completed')
                       ->orderBy('completed_at', 'desc')
                       ->get();

        $totalRevenue = $today_sales->sum('total_amount');
        $totalProfit = $today_sales->sum('total_profit');
        $totalTransactions = $today_sales->count();

        $list_low_stocks = Products::select(
            "products.*",
                     "categories.category_name",
        )
        ->join('categories', 'categories.id','=', 'products.category_id')
        ->whereColumn('minimum_stock', '>', 'stock_quantity')->get();

        $product_sale = salesItem::select(
            "products.name",
            DB::raw("SUM(quantity) as total_sold"),
            DB::raw("SUM(subtotal) as total_amount"),
            DB::raw("SUM(profit) as total_profit"),
        )
        ->join('products', 'products.id', '=', 'sales_items.product_id')
        ->groupBy('sales_items.product_id')
        ->get();
        return view('backend.reports', compact(
            'totalRevenue', 
            'totalProfit', 
            'totalTransactions', 
            'list_low_stocks', 
            'today_sales', 
            'all_sales',
            'product_sale'));
    }
}