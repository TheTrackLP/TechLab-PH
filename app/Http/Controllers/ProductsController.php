<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Supplier;
use App\Models\StockMovements;
use Illuminate\Http\Request;
use Validator;

class ProductsController extends Controller
{
    public function ProductsIndex(){
        $categories = Categories::all();
        $suppliers = Supplier::where('is_active', 1)->get();
        $products = Products::select(
            "products.*",
                     "categories.category_name",
            )
            ->join("categories", "categories.id", "=", "products.category_id")
            ->get();
        return view('backend.product.products', compact('categories', 'products', 'suppliers'));
    }

    public function ProductsStore(Request $request){
        $valid = Validator::make($request->all(), [
            "name" => "required",
            "brand" => "required",
            "category_id" => "required",
            "supplier_id" => "required",
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

        $product = Products::create([
            "name" => $request->name,
            "image" => $path,
            "brand" => $request->brand,
            "category_id" => $request->category_id,
            "supplier_id" => $request->supplier_id,
            "sku" => $request->sku,
            "description" => $request->description,
            "stock_quantity" => $request->stock_quantity,
            "minimum_stock" => $request->minimum_stock,
            "cost_price" => $request->cost_price,
            "selling_price" => $request->selling_price,
        ]);

        StockMovements::create([
            'product_id' => $product->id,
            'type' => 'restock',
            'quantity' => $request->stock_quantity,
            'reference_id' => null,
            'notes' => null,
            'created_by' => null,
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
            "supplier_id" => "required",
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

        $product = Products::findOrFail($prod_id);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($request->image))) {
                unlink(public_path($product->image));
            }
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('img/products'), $filename);
            $product->image = 'img/products/'.$filename;
        }
            $product->name = $request->name;
            $product->brand = $request->brand;
            $product->category_id = $request->category_id;
            $product->supplier_id = $request->supplier_id;
            $product->sku = $request->sku;
            $product->description = $request->description;
            $product->stock_quantity = $request->stock_quantity;
            $product->minimum_stock = $request->minimum_stock;
            $product->cost_price = $request->cost_price;
            $product->selling_price = $request->selling_price;

            $product->save();

        return redirect()->route('products.index')
                         ->with([
                            'message' => 'Product Updated Successfully!',
                            'alert-type' => 'success',
                        ]);
    }

    public function ProductStatus($id){

        $product = Products::findOrFail($id);

        if($product->is_active == 1){
            $product->update([
                'is_active' => 0
            ]);
            }elseif($product->is_active == 0){
                $product->update([
                    'is_active' => 1
                    ]);
        }

        return redirect()->route('products.index')
                         ->with([
                            'message' => 'Product Status Changed Successfully!',
                            'alert-type' => 'success',
                        ]);
    }
}