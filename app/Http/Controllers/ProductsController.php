<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use Illuminate\Http\Request;
use Validator;

class ProductsController extends Controller
{
    public function ProductsIndex(){
        $categories = Categories::all();
        $products = Products::select(
            "products.*",
                     "categories.category_name"
            )
            ->join("categories", "categories.id", "=", "products.category_id")
            ->get();
        return view('backend.product.products', compact('categories', 'products'));
    }

    public function ProductsStore(Request $request){
        $valid = Validator::make($request->all(), [
            "name" => "required",
            "brand" => "required",
            "category_id" => "required",
            "sku" => "required",
            "description" => "required",
            "stock_quantity" => "required",
            "minimum_stock" => "required",
            "cost_price" => "required",
            "selling_price" => "required",
        ]);

        if($valid->fails()){
            return redirect()->route('products.index')
                             ->with([
                                'message' => 'Error, Try again!',
                                'alert-type' => 'error',
                             ]);
        }

        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('assets/img/products'), $filename);
            $path = 'assets/img/products/'.$filename;
        }

        Products::create([
            "name" => $request->name,
            "image" => $path,
            "brand" => $request->brand,
            "category_id" => $request->category_id,
            "sku" => $request->sku,
            "description" => $request->description,
            "stock_quantity" => $request->stock_quantity,
            "minimum_stock" => $request->minimum_stock,
            "cost_price" => $request->cost_price,
            "selling_price" => $request->selling_price,
        ]);

        return redirect()->route('products.index')
                         ->with([
                            'message' => 'Product Added Successfully!',
                            'alert-type' => 'success',
                        ]);
    }

    public function ProductEdit($id){
        $product_id = Products::findOrFail($id);
        return response()->json([
            'data'=>$product_id,
        ]);
    }

    public function ProductUpdate(Request $request){
        $prod_id = $request->id;

$valid = Validator::make($request->all(), [
            "name" => "required",
            "brand" => "required",
            "category_id" => "required",
            "sku" => "required",
            "description" => "required",
            "stock_quantity" => "required",
            "minimum_stock" => "required",
            "cost_price" => "required",
            "selling_price" => "required",
        ]);

        if($valid->fails()){
            return redirect()->route('products.index')
                             ->with([
                                'message' => 'Error, Try again!',
                                'alert-type' => 'error',
                             ]);
        }

        $path = null;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($request->image && file_exists(public_path($request->image))) {
                unlink(public_path($request->image));
            }
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);
            $request->image = 'assets/img/products/'.$filename;
        }


        Products::create([
            "name" => $request->name,
            "image" => $path,
            "brand" => $request->brand,
            "category_id" => $request->category_id,
            "sku" => $request->sku,
            "description" => $request->description,
            "stock_quantity" => $request->stock_quantity,
            "minimum_stock" => $request->minimum_stock,
            "cost_price" => $request->cost_price,
            "selling_price" => $request->selling_price,
        ]);

        return redirect()->route('products.index')
                         ->with([
                            'message' => 'Product Added Successfully!',
                            'alert-type' => 'success',
                        ]);
    }
}