<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AdminController::class, 'AdminDashboard'])->name('admin.dash');

Route::controller(ProductsController::class)->group(function(){
    Route::get('/Products', 'ProductsIndex')->name('products.index');
    Route::post('/Products/store', 'ProductsStore')->name('products.store');
    Route::post('/Products/update/{id}', 'ProductsUpdate')->name('products.update');
    Route::post('/Products/update/product-status/{id}', 'ProductsUpdateStatus')->name('products.status');
});

Route::controller(SalesController::class)->group(function(){
    Route::get('/sales/products', 'getProducts')->name('sales.index');
    Route::post('/sales/products/store', 'CompleteSale')->name('sales.store');
});

Route::controller(StocksController::class)->group(function(){
    Route::get('/restocks', 'RestockIndex')->name('stocks.index');
    Route::post('/restocks/store', 'RestockStore')->name('stocks.store');
});

Route::controller(CategoriesController::class)->group(function(){
    Route::get('/categories', 'CategoriesIndex')->name('category.index');
    Route::post('/categories/store', 'CategoriesStore')->name('category.store');
    Route::post('/categories/update/{id}', 'CategoriesUpdate')->name('category.update');
    Route::get('/categories/delete/{id}', 'CategoriesDelete')->name('category.delete');
});

Route::controller(SupplierController::class)->group(function(){
    Route::get('/suppliers', 'SupplierIndex')->name('supplier.index');
    Route::post('/suppliers/store', 'SupplierStore')->name('supplier.store');
    Route::post('/suppliers/update/{id}', 'SupplierUpdate')->name('supplier.update');
});