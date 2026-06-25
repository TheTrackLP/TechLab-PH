<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function CategoriesIndex(){
        return inertia('Admin/Backend/Categories',[
            'categories'=>Categories::all(),
        ]);
    }

    public function CategoriesStore(Request $request){
        $valid = Validator::make($request->all(),[
            'category_name' => 'required',
            'category_desc' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('category.index')->with(
                'error', 'Error, Try Again!',
            );
        }

        Categories::create($request->all());

        return redirect()->route('category.index')->with(
            'success', 'Category Added Successfully!',
        );
    }

    public function CategoriesUpdate(Request $request){
        $category_id = $request->id;
        $valid = Validator::make($request->all(),[
            'category_name' => 'required',
            'category_desc' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('category.index')->with(
                'error', 'Error, Try Again!',
            );
        }

        Categories::findorfail($category_id)->update($request->all());

        return redirect()->route('category.index')->with(
            'success', 'Category Updated Successfully!',
        );
    }
        
    public function CategoriesDelete($id){
        Categories::findorfail($id)->delete();
        
        return redirect()->route('category.index')->with(
            'warning', 'Category Deleted Successfully!',
        );
    }
}
