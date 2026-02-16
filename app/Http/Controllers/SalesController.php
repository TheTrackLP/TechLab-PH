<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function SalesIndex(){
        $products = Products::where('is_active', 1)->get();
        return view('backend.sales', compact('products'));
    }
}