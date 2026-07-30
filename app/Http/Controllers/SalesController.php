<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\SaleItems;
use App\Models\Sales;
use App\Models\StockMovements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function getProducts(){
        $products = DB::table('products')
                    ->select(
                        'products.*',
                        'categories.category_name as cat_name'
                    )
                    ->join('categories', 'categories.id', '=', 'products.category_id')
                    ->get();
        return inertia('Admin/Backend/Sales', [
            'products'=>$products,
            'categories'=>Categories::all(),
        ]);
    }

    public function CompleteSale(Request $request){
        DB::beginTransaction();

        try {
            $products = $request->products;
            $amountPaid = $request->amount_paid;
            $change = $request->change;

            $sale = Sales::create([
                'invoice_no' => null,
                'customer_name' => null,
                'total_amount' => 0,
                'total_profit' => 0,
                'payment_type' => 'cash',
                'amount_paid' => $amountPaid,
                'change_amount' => $change,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $totalAmount = 0;
            $totalProfit = 0;

            foreach ($products as $item) {
                $product = Products::lockForUpdate()->findorfail($item['product_id']);
                
                if($product->stock_quantity < $item['qty']){
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                
                $subtotal = $item['qty'] * $product->selling_price;
                $profit = ($product->cost_price - $product->selling_price) * $item['qty'];

                SaleItems::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['qty'],
                    'cost_price_snapshot' => $product->cost_price,
                    'selling_price_snapshot' => $product->selling_price,
                    'subtotal' => $subtotal,
                    'profit' => $profit,
                ]);

                $product->stock_quantity -= $item['qty'];
                $product->save();

                $totalAmount += $subtotal;
                $totalProfit += $profit;

                StockMovements::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => -$item['qty'],
                    'reference_id' => $sale->id,
                    'notes' => null,
                    'created_by' => null,
                ]);
            }
            $year = now()->year;

            $lastSale = Sales::whereYear('created_at', $year)
                               ->whereNotNull('invoice_no')
                               ->orderBy('id', 'desc')
                               ->first();

            $nextNumber = $lastSale ? intval(substr($lastSale->invoice_no, -5)) + 1 : 1;

            $invoice = "TL-" . $year . "-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            $sale->update([
                'invoice_no' => $invoice,
                'total_amount' => $totalAmount,
                'total_profit' => $totalProfit,
            ]);

            DB::commit();

            return redirect()->route('sales.index')->with(
                'success', 'Sales Success',
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
                ], 400);
            }
    }
}