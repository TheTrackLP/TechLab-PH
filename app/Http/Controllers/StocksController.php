<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class StocksController extends Controller
{
    public function RestockIndex(){
        return inertia('Admin/Backend/Stocks', [
            'suppliers'=>Suppliers::all(),
            'products'=>Products::all(),
        ]);
    }
}
