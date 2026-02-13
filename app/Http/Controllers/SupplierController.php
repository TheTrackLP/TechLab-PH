<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Validator;

class SupplierController extends Controller
{
    public function SupplierIndex(){
        $suppliers = Supplier::all();
        return view('backend.suppliers', '');
    }

    public function SupplierStore(Request $request){
        $valid = Validator::make($request->all(), [
            "name" => "required"
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
}