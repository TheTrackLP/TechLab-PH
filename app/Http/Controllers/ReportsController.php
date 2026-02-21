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

    public function ViewInvoice($id){
        $invoice = Sales::findOrFail($id);

        $items = salesItem::select(
            "sales_items.*",
            "sales.*",
            "products.name",
        )
        ->join('sales', 'sales.id', '=', 'sales_items.sale_id')
        ->join('products', 'products.id', '=', 'sales_items.product_id')
        ->where('sales_items.sale_id', $invoice->id)
        ->get();
        return response()->json([
            'invoice'=>$invoice,
            'items'=>$items,
        ]);
    }

    // public function PrintInvoice(Request $request){
    //     $invoice_id = $request->id;
    //     $invoice = Sales::findOrFail($invoice_id)->first();

    //     $items = salesItem::select(
    //         "sales_items.*",
    //         "sales.*",
    //         "products.name",
    //     )
    //     ->join('sales', 'sales.id', '=', 'sales_items.sale_id')
    //     ->join('products', 'products.id', '=', 'sales_items.product_id')
    //     ->where('sales_items.sale_id', $invoice->id)
    //     ->get();

    //     return view('backend.print_invoice', compact(
    //         'invoice',
    //         'items'
    //     ));
    // }

    public function GenerateDateRangeInvoice(Request $request){
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $dateFilter_invoice = Sales::whereBetween('completed_at', [$fromDate, $toDate])
                             ->get();
        return view('backend.print.print_daterange_inovice', compact(
            'dateFilter_invoice',
            'fromDate',
            'toDate'
            ));
    }

    public function GenerateDateRangeProductSale(Request $request){
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;
        
        $dateFilter_productSale = salesItem::select(
            "products.name",
            DB::raw("SUM(quantity) as total_sold"),
            DB::raw("SUM(subtotal) as total_amount"),
            DB::raw("SUM(profit) as total_profit"),
        )
        ->join('products', 'products.id', '=', 'sales_items.product_id')
        ->groupBy('sales_items.product_id')
        ->get();

        

        return view('backend.print.print_daterange_productsale', compact(
            'fromDate',
            'toDate',
            'dateFilter_productSale'    
            ));
    }

    public function GenerateLowStocksReport(){
        $list_low_stocks = Products::select(
            "products.*",
                     "categories.category_name",
        )
        ->join('categories', 'categories.id','=', 'products.category_id')
        ->whereColumn('minimum_stock', '>', 'stock_quantity')
        ->get();

        $totalLowStocks = Products::whereColumn('stock_quantity', '<=', 'minimum_stock')
                                ->count();
        $totalOutStocks = Products::where('stock_quantity', 0)->count();
    
        return view('backend.print.print_lowstocks', compact(
            'list_low_stocks',
            'totalLowStocks',
            'totalOutStocks'
            ));
    }
}