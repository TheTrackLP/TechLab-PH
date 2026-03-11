<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\RepairItems;
use App\Models\Repairs;
use App\Models\Sales;
use App\Models\salesItem;
use App\Models\StockMovements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RepairController extends Controller
{
    public function RepairIndex(){
        $categories = Categories::all();
        $products = Products::all();
        $repairs = Repairs::all();
        return view('backend.repairs', compact(
            'repairs',
            'products',
            'categories'
            ));
    }

    public function GenerateRepairForm(Request $request){
        $valid = Validator::make($request->all(), [
            'customer_name' => 'required',
            'contact_number' => 'required',
            'device_type' => 'required',
            'device_brand' => 'required',
            'issue_description' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('repair.index')
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error'
                             ]);
        }
        
        $year = now()->year;

        $lastRepair = Repairs::whereYear('created_at', $year)
                             ->whereNotNull('repair_no')
                             ->orderBy('id', 'desc')
                             ->first();

        $lastNumber = $lastRepair ? intval(substr($lastRepair->repair_no, -5)) + 1 : 1;
        
        $repair_no = "R-". $year. "-". str_pad($lastNumber, 5, '0', STR_PAD_LEFT);

        Repairs::create([
            'repair_no' => $repair_no,
            'customer_name' => $request->customer_name,
            'contact_number' => $request->contact_number,
            'device_type' => $request->device_type,
            'device_brand' => $request->device_brand,
            'issue_description' => $request->issue_description,
            'diagnosis' => null,
            'labor_fee' => 0,
            'total_parts_amount' => 0,
            'total_amount' => 0,
            'status' => 'pending_diagnosis',
        ]);

        return redirect()->route('repair.index')->with([
            'message' => 'Repair Generated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function getRepairDetails($id){
        $repair = Repairs::findOrFail($id);

        $repairtParts = RepairItems::select(
            'repair_items.*',
            'repairs.*',
            'products.name',
        )
        ->join('repairs', 'repairs.id', '=', 'repair_items.repair_id')
        ->join('products', 'products.id', '=', 'repair_items.product_id')
        ->where('repair_items.repair_id', $repair->id)
        ->get();

        return response()->json([
            'repair'=>$repair,
            'parts'=>$repairtParts,
        ]);
    }

    public function RepairUpdate(Request $request){
        DB::beginTransaction();
        try {
        $repair_id = $request->repairId;
        $repairParts = $request->repairParts;
        $diagnosis = $request->diagnosis;
        $labor_fee = $request->labor_fee;
        $totalOverallPay = $request->totalOverallPay;

        $repair = Repairs::findOrFail($repair_id);

        RepairItems::where('repair_id', $repair_id)
                                        ->delete();

        $totalAmount = 0;

        foreach ($repairParts as $item) {
            $product = Products::lockForUpdate()->findOrFail($item['product_id']);

            //Check Stock of Item
            if($product->stock_quantity < $item['quantity']){
                throw new \Exception("Insufficient stock for {$product->name}");
            }

            $subTotal = $item['selling_price'] * $item['quantity'];

            RepairItems::create([
                'repair_id' => $repair_id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'cost_price_snapshot' => $product->cost_price,
                'selling_price_snapshot' => $product->selling_price,
                'subTotal' => $subTotal,
            ]);

            $product->save();

            $totalAmount += $subTotal;
        }

        $repair->update([
            'diagnosis' => $diagnosis,
            'labor_fee' => $labor_fee,
            'status' => 'awaiting_approval',
            'total_parts_amount' => $totalAmount,
            'total_amount' => $totalOverallPay,
        ]);

        DB::commit();

        return response()->json([
            'message' => 'Restock completed successfully.',
            'repair_no' => $repair->repair_no,
        ]);
        
        }catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
                ], 400);
        }
    }

    public function getProductsbyCategory($id){
        $products = Products::where('category_id', '=', $id)->get();

        return response()->json([
            'products'=>$products,
        ]);
    }

    public function changeRepairStatus(Request $request, $id){
        $repair_id = Repairs::findOrFail($id);

        $statusChange = $request->statusChange;
        $labor_fee = $request->labor_fee;

        $pickup_deadline = date('Y-m-d', strtotime(date('Y-m-d') . "+" . " 7 days"));

        if($statusChange == 'in_progress'){
            $repair_id->update([
                'status' => $statusChange
            ]);
        } elseif($statusChange == 'completed'){
            $repair_id->update([
                'status' => $statusChange,
                'completed_at' => now(),
                'pickup_deadline' => $pickup_deadline,
            ]);
        } elseif($statusChange == 'generate_sale'){
            DB::beginTransaction();
            try{
                $generateSaleParts = $request->generateSaleParts;
                $change = $request->change;
                $amountPaid = $request->amountPaid;

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
                $overAllProft = 0;

                foreach ($generateSaleParts as $item) {
                    $product = Products::lockForUpdate()->findOrFail($item['product_id']);

                    if($product->stock_quantity < $item['quantity']){
                        throw new \Exception("Insufficient stock for {$product->name}");
                    }
                    $subTotal = $item['quantity'] * $product->selling_price;
                    $profit = ($product->selling_price - $product->cost_price) * $item['quantity'];

                    salesItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'cost_price_snapshot' => $product->cost_price,
                        'selling_price_snapshot' => $product->selling_price,
                        'subtotal' => $subTotal,
                        'profit' => $profit,
                    ]);

                    $product->stock_quantity -= $item['quantity'];
                    $product->save();

                    $totalAmount += $subTotal;
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

                $year = now()->year;

                $lastSale = Sales::whereYear('created_at', $year)
                                ->whereNotNull('invoice_no')
                                ->orderBy('id', 'desc')
                                ->first();

                $nextNumber = $lastSale ? intval(substr($lastSale->invoice_no, -5)) + 1: 1;

                $invoice = "TL-" . $year . "-". str_pad($nextNumber, 5, "0", STR_PAD_LEFT);

                $overAllAmount = $totalAmount + $labor_fee;
                $overAllProft = $totalProfit + $labor_fee;

                $sale->update([
                    'invoice_no' => $invoice,
                    'total_amount' => $overAllAmount,
                    'total_profit' => $overAllProft,
                ]);

                $repair_id->update([
                    'sale_id'=>$sale->id,
                ]);

                DB::commit();

                return response()->json([
                    'message' => 'Sale completed successfully.',
                    'invoice_no' => $invoice,
                    'statusChange' => $statusChange,
                ]);
            }catch (\Exception $e) {
                DB::rollBack();
                
                return response()->json([
                    'message' => $e->getMessage()
                    ], 400);
            }   
        } elseif($statusChange == 'released'){
            $repair_id->update([
                'status' => $statusChange,
                'released_at' => now(),
            ]);
        } elseif($statusChange == 'cancelled'){
            $repair_id->update([
                'status' => $statusChange
            ]);
        } elseif($statusChange == 'abandoned'){
            $repair_id->update([
                'status' => $statusChange
            ]);
        }

        return response()->json([
            'message' => 'Repair approved successfully',
            'repair_no' => $repair_id->repair_no,
            'status' => $statusChange,
        ]);
    }
}