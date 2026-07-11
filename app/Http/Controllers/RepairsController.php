<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RepairsController extends Controller
{
    public function RepairIndex(){
        return inertia('Admin/Backend/Repairs');
    }
}
