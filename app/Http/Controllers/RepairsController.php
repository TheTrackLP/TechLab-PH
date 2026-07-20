<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\RepairItems;
use App\Models\Repairs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RepairsController extends Controller
{
    public function RepairIndex(){
        return inertia('Admin/Backend/Repairs', [
            'categories'=>Categories::all(),
            'repairs'=>Repairs::all(),
        ]);
    }

    public function RepairStore(Request $request) {
        $valid = Validator::make($request->all(), [
            'customer_name' =>  "required",
            'contact_number' =>  "required",
            'device_type' =>  "required",
            'device_brand' =>  "required",
            'issue_description' =>  "required",
        ]);

        if($valid->fails()){
            return redirect()->route('repair.index')->with(
                'error', 'Error, Try Again!',
            );
        }

        $year = now()->year;

        $lastRepair = Repairs::whereYear('created_at', $year)
                             ->whereNotNull('repair_no')
                             ->orderBy('id', 'desc')
                             ->first();

        $lastNumber = $lastRepair ? intval(substr($lastRepair->repair_no, -5)) + 1 : 1;
        
        $repair_no = "R-". $year. "-". str_pad($lastNumber, 5, '0', STR_PAD_LEFT);

        Repairs::create([
            'repair_no' =>  $repair_no,
            'customer_name' =>  $request->customer_name,
            'contact_number' =>  $request->contact_number,
            'device_type' =>  $request->device_type,
            'device_brand' =>  $request->device_brand,
            'issue_description' =>  $request->issue_description,
        ]);

        return redirect()->route('repair.index')->with(
            'success', 'Repair Ticket Created',
        );
    }


    public function getCategoryProducts($id){
        $products = Products::where('category_id', $id)->get();

        return response()->json($products);
    }

    public function RepairUpdate(Request $request){
        DB::beginTransaction();

        try {
            $repairId = $request->repairId;
            $diagnosis = $request->diagnosis;
            $labor_fee = $request->labor_fee;
            $repairParts = $request->repairParts;

            RepairItems::where('repair_id', $repairId)->delete();

            $totalAmount = 0;


            if (empty($request->repairParts)) {
                Repairs::findOrFail($repairId)->update([
                    'labor_fee' => $request->labor_fee,
                    'diagnosis' => $request->diagnosis,
                    'status' => 'awaiting_approval',
                ]);
            }
            {
                DB::commit();
                return redirect()->route('repair.index')->with(
                    'success', 'Repair Updated Successfully',
                );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage()
                ], 400);
        }
    }

    public function ChangeRepairStatus(Request $request){
        $repair_id = Repairs::findOrFail($request->changeRepairStatusID);
        $changeStatus = $request->btnRepairChangeStatus;

        if($changeStatus == 'in_progress'){
            $repair_id->update([
                'status'=>$changeStatus,
            ]);
        }

        return redirect()->route('repair.index')->with(
            'success', 'Repair Status Updated Successfully to '. $changeStatus,
        );
    }
}
