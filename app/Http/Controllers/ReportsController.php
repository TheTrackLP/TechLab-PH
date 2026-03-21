<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Repairs;
use App\Models\ReturnItems;
use App\Models\Sales;
use App\Models\Products;
use App\Models\RepairItems;
use App\Models\Returns;
use App\Models\salesItem;
use App\Models\StockMovements;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function ReportsIndex(){
        $categories = Categories::all();
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
        ->leftJoin("sales", "sales.id", "returns.sale_id")
        ->orderBy('returns.id', 'desc')
        ->get();

        $repairs = Repairs::select(
            'repairs.*',
            'sales.invoice_no'
            )
        ->leftJoin("sales", "sales.id", "repairs.sale_id")
        ->orderBy('repairs.id', 'desc')
        ->get();

        $inventories = Products::select(
            'products.*',
            'categories.category_name',
        )
        ->join("categories", "categories.id", "=", "products.category_id")
        ->get();

        return view('backend.reports.reports', compact(
            'totalRevenue', 
            'totalProfit', 
            'totalTransactions', 
            'list_low_stocks', 
            'today_sales', 
            'all_sales',
            'product_sale',
            'stockMove',
            'returns',
            'repairs',
            'inventories',
            'categories'
            ));
    }

    public function ViewInvoiceInfo($id){
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

    public function ViewRetunInfo($id){
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
    public function ViewRepairInfo($id){
        $repair_info = Repairs::select(
            'repairs.*',
            'sales.invoice_no',
        )
        ->leftJoin('sales', 'sales.id', 'repairs.sale_id')
        ->where('repairs.id', $id)
        ->first();

        $repair_items = RepairItems::select(
            'repair_items.*',
            'repairs.*',
            'products.name',
        )
        ->leftJoin("repairs", "repairs.id", "=", "repair_items.repair_id")
        ->leftJoin('products', 'products.id', '=', 'repair_items.product_id')
        ->where("repair_items.repair_id", $repair_info->id)
        ->get();

        return response()->json([
            'repair_info'=>$repair_info,
            'repair_items'=>$repair_items,
        ]);
    }

    public function InventoryReportPrint(Request $request){
        $category = $request->iCategory;
        $status = $request->iStatus;

        $inventories = Products::select(
            'products.*',
            'categories.category_name',
        )
        ->join("categories", "categories.id", "=", "products.category_id")
        ->when($category, function ($query) use ($category) {
            $query->where("products.category_id", $category);
        })
        ->when($status, function ($query) use ($status) {
            if ($status == 'out') {
                $query->where('stock_quantity', 0);
            } elseif ($status == 'low') {
                $query->whereColumn('stock_quantity', '<=', 'minimum_stock')
                      ->where('stock_quantity', '>', 0);
            } elseif ($status == 'normal') {
                $query->whereColumn('stock_quantity', '>', 'minimum_stock');
            }
        })->get();

        $totalProducts = $inventories->count();
        $lowStocks = Products::whereColumn('stock_quantity', '<=', 'minimum_stock')
                                ->count();
        $outStocks = Products::where('stock_quantity', 0)->count();
        
        return view('backend.print.print_inventory_report', compact(
            'inventories',
            'totalProducts',
            'lowStocks',
            'outStocks',
            ));
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