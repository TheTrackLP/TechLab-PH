<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Suppliers;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function ProductsIndex(){
        return inertia('Admin/Backend/Products', [
            'suppliers'=>Suppliers::all(),
            'categories'=>Categories::all(),
        ]);
    }
}
