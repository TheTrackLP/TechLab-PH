<?php

namespace App\Http\Controllers;

use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function SupplierIndex(){
        return inertia('Admin/Backend/Suppliers', [
            'suppliers'=>Suppliers::all(),
        ]);
    }

    public function SupplierStore(Request $request){
        $valid = Validator::make($request->all(),[
            'name' => 'required',
            'supplier_type' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('supplier.index')->with(
                'error', 'Error, Try Again!',
            );
        }
            
        Suppliers::create($request->all());
        return redirect()->route('supplier.index')->with(
            'success', 'Supplier Added Successfully',
        );
    }
}