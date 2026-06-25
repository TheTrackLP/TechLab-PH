<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'AdminDashboard'])->name('admin.dash');

Route::controller(CategoriesController::class)->group(function(){
    Route::get('/categories', 'CategoriesIndex')->name('category.index');
    Route::post('/categories/store', 'CategoriesStore')->name('category.store');
    Route::post('/categories/update/{id}', 'CategoriesUpdate')->name('category.update');
    Route::get('/categories/delete/{id}', 'CategoriesDelete')->name('category.delete');
});

Route::controller(SupplierController::class)->group(function(){
    Route::get('/suppliers', 'SupplierIndex')->name('supplier.index');
});