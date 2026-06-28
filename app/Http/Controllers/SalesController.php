<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function getProducts(){
        return inertia('Admin/Backend/Sales', [
            'products'=>Products::all(),
        ]);
    }
}