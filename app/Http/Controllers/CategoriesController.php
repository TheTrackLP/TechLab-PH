<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use DB;
use Validator;

class CategoriesController extends Controller
{
    public function CategoriesIndex(){
        $categories = Categories::all();
        return view('backend.categories', compact('categories'));
    }

    public function CategoriesStore(Request $request){
        $valid = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('category.index')
                             ->with([
                                'message'=>'Error, Try Again!',
                                'alert-type'=>'error',
                             ]);
        }

        Categories::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);
        
        return redirect()->route('category.index')
                         ->with([
                            'message'=>'Category Added Successfully!',
                            'alert-type'=>'success',
                         ]);
    }

    public function CategoriesEdit($id){
        $data = Categories::findOrFail($id);

        return response()->json([
            'data'=>$data
        ]);
    }

    public function CategoriesUpdate(Request $request){
        $cat_id = $request->id;
        $valid = Validator::make($request->all(), [
            'category_name' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('category.index')
                             ->with([
                                'message'=>'Error, Try Again!',
                                'alert-type'=>'error',
                             ]);
        }

        Categories::findOrFail($cat_id)->update([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);
        
        return redirect()->route('category.index')
                         ->with([
                            'message'=>'Category Updated Successfully!',
                            'alert-type'=>'success',
                         ]);
    }

    public function CategoriesDelete($id){
        Categories::findOrFail($id)->delete();

        return redirect()->route('category.index')
                         ->with([
                            'message'=>'Category Deleted Successfully!',
                            'alert-type'=>'warning',
                         ]);
    }
}