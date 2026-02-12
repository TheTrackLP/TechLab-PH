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
            return redirect()->route('category.index');
        }

        Categories::create([
            'category_name' => $request->category_name,
            'category_description' => $request->category_description,
        ]);
            return redirect()->route('category.index');
    }
}