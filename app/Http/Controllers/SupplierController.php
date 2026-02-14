<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends Controller
{
    public function SupplierIndex(){
        $suppliers = Supplier::all();
        return view('backend.suppliers',compact('suppliers'));
    }

    public function SupplierStore(Request $request){
        $valid = Validator::make($request->all(), [
            "name" => "required",
            "supplier_type" => "required",
        ]);

        if($valid->fails()){
            return redirect()->route('supplier.index')
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error',
                             ]);
        }

        Supplier::create([
            "name" => $request->name,
            "supplier_type" => $request->supplier_type,
            "contact_person" => $request->contact_person,
            "phone" => $request->phone,
            "address" => $request->address,
            "notes" => $request->notes,
        ]);

        return redirect()->route('supplier.index')
                         ->with([
                            'message' => 'Supplier Added Successfully',
                            'alert-type' => 'success',
                        ]);
    }

    public function SupplierEdit($id){
        $data = Supplier::findOrFail($id);
        return response()->json([
            'data'=>$data,
        ]);
    }

    public function SupplierUpdate(Request $request){
        $supplier_id = $request->id;
        $valid = Validator::make($request->all(), [
            "name" => "required",
            "supplier_type" => "required",
        ]);

        if($valid->fails()){
            return redirect()->route('supplier.index')
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error',
                             ]);
        }

        Supplier::findOrFail($supplier_id)->update([
            "name" => $request->name,
            "supplier_type" => $request->supplier_type,
            "contact_person" => $request->contact_person,
            "phone" => $request->phone,
            "address" => $request->address,
            "notes" => $request->notes,
        ]);

        return redirect()->route('supplier.index')
                         ->with([
                            'message' => 'Supplier Updated Successfully',
                            'alert-type' => 'success',
                        ]);
    }
    
    public function SupplierStatus($id){
        $supplier = Supplier::findOrFail($id);

        if($supplier->is_active == 1){
            $supplier->update([
                'is_active' => 0
            ]);
            }elseif($supplier->is_active == 0){
                $supplier->update([
                    'is_active' => 1
                    ]);
        }

        return redirect()->route('supplier.index')
                         ->with([
                            'message' => 'Supplier Change Status Successfully!',
                            'alert-type' => 'success',
                        ]);
    }
}