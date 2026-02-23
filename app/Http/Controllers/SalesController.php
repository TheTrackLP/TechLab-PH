<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Sales;
use App\Models\salesItem;
use App\Models\StockMovements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try{
            $cart = $request->cart;
            $change = $request->change;
            $amountPaid = $request->amount_paid;

            //Create sale Table First
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

            foreach ($cart as $item) {
                $product = Products::lockForUpdate()->findOrFail($item['product_id']);

                //Check Stock of Item
                if($product->stock_quantity < $item['quantity']){
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                $subtotal = $item['quantity'] * $product->selling_price;
                $profit = ($product->selling_price - $product->cost_price) * $item['quantity'];

                salesItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'cost_price_snapshot' => $product->cost_price,
                    'selling_price_snapshot' => $product->selling_price,
                    'subtotal' => $subtotal,
                    'profit' => $profit,
                ]);

                //Deduct Stock
                $product->stock_quantity -= $item['quantity'];
                $product->save();

                $totalAmount += $subtotal;
                $totalProfit += $profit;

                StockMovements::create([
                    'product_id' => $product->id,
                    'type' => 'sale',
                    'quantity' => -$item['quantity'],
                    'reference_id' => $sale->id,
                    'notes' => null,
                    'created_by' => null,
                ]);
            }

            //Generate Invoice
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

            return response()->json([
                'message' => 'Sale completed successfully.',
                'invoice_no' => $invoice,
            ]);

        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
                ], 400);
            }
    }
}