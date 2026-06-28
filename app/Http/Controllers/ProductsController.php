<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
    public function ProductsIndex(){
        return inertia('Admin/Backend/Products', [
            'products'=>Products::all(),
            'suppliers'=>Suppliers::all(),
            'categories'=>Categories::all(),
        ]);
    }
    public function ProductsStore(Request $request){
        $valid = Validator::make($request->all(), [
            'category_id' => 'required',
            'supplier_id' => 'required',
            'brand' => 'required',
            'name' => 'required',
            'sku' => 'required',
            'description' => 'required',
            'stock_quantity' => 'required',
            'minimum_stock' => 'required',
            'cost_price' => 'required',
            'selling_price' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('products.index')->with(
                'error', 'Error, Try Again!',
            );
        }

        Products::create([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'brand' => $request->brand,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'stock_quantity' => $request->stock_quantity,
            'minimum_stock' => $request->minimum_stock,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
        ]);

        return redirect()->route('products.index')->with(
            'success', 'Product Successfully Added',
        );
    }
    public function ProductsUpdate(Request $request){
        $valid = Validator::make($request->all(), [
            'category_id' => 'required',
            'supplier_id' => 'required',
            'brand' => 'required',
            'name' => 'required',
            'sku' => 'required',
            'description' => 'required',
            'stock_quantity' => 'required',
            'minimum_stock' => 'required',
            'cost_price' => 'required',
            'selling_price' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('products.index')->with(
                'error', 'Error, Try Again!',
            );
        }

        Products::findorfail($request->id)->update([
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'brand' => $request->brand,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'stock_quantity' => $request->stock_quantity,
            'minimum_stock' => $request->minimum_stock,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
        ]);

        return redirect()->route('products.index')->with(
            'success', 'Product Successfully Updated!',
        );
    }

    public function ProductsUpdateStatus($id){
        $product = Products::findorfail($id);

        if($product->is_active == 1){
            $product->update([
                'is_active' => 0
            ]);
        } elseif ($product->is_active == 0){
            $product->update([
                'is_active' => 1
            ]);
        }

        return redirect()->route('products.index')->with(
            'success', 'Product Status Updated!',
        );
    }
}