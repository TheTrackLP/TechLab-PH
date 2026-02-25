<?php

namespace App\Http\Controllers;

use App\Models\Repairs;
use Illuminate\Http\Request;
use Validator;

class RepairController extends Controller
{
    public function RepairIndex(){
        $repairs = Repairs::all();
        return view('backend.repairs', compact('repairs'));
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
        $repair_id = Repairs::findOrFail($id);
        return response()->json([
            'repair'=>$repair_id,
        ]);
    }
}