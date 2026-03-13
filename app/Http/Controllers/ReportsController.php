<?php

namespace App\Http\Controllers;

use App\Models\ReturnItems;
use App\Models\Sales;
use App\Models\Products;
use App\Models\Returns;
use App\Models\salesItem;
use App\Models\StockMovements;
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

        $stockMove = StockMovements::select(
            'stock_movements.*',
            'products.name',
            DB::raw("CASE 
                                WHEN stock_movements.type = 'sale' THEN sales.invoice_no 
                                WHEN stock_movements.type = 'restock' THEN restocks.reference_no 
                                WHEN stock_movements.type = 'return' THEN returns.return_no 
                                ELSE 'Invalid' 
                            END AS reference_no")
        )
        ->leftJoin('products', 'products.id', '=', 'stock_movements.product_id')
        ->leftJoin('sales', 'sales.id', '=', 'stock_movements.reference_id')
        ->leftJoin('restocks', 'restocks.id', '=', 'stock_movements.reference_id')
        ->leftJoin('returns', 'returns.id', '=', 'stock_movements.reference_id')
        ->get();

        $returns = Returns::select(
            'returns.*',
            'sales.invoice_no'
            )
        ->join("sales", "sales.id", "returns.sale_id")
        ->join("return_items", "return_items.id", "returns.id")
        ->orderBy('returns.id', 'desc')
        ->get();

        return view('backend.reports', compact(
            'totalRevenue', 
            'totalProfit', 
            'totalTransactions', 
            'list_low_stocks', 
            'today_sales', 
            'all_sales',
            'product_sale',
            'stockMove',
            'returns'
            ));
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

    public function ViewRetuns($id){
        $return = Returns::select(
            'returns.*',
            'sales.invoice_no',
            DB::raw("count(return_items.return_id) as total_items"),
            )
        ->join('sales', 'sales.id', '=', 'returns.sale_id')
        ->join('return_items', 'return_items.return_id', '=', 'returns.id')
        ->where('returns.id', $id)
        ->groupBy('return_items.return_id')
        ->first();

        $return_items = ReturnItems::select(
            'return_items.*',
            'returns.*',
            'products.name',
        )
        ->leftJoin('returns', 'returns.id', '=', 'return_items.return_id')
        ->leftJoin('products', 'products.id', '=', 'return_items.product_id')
        ->where('return_items.return_id', $return->id)
        ->groupBy('return_items.id')
        ->get();

        return response()->json([
            'return_info'=>$return,
            'return_items'=>$return_items
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