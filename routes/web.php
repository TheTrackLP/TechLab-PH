<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SupplierController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function(){
    Route::controller(AdminController::class)->group(function(){
        Route::get('/admin/dashboard', 'AdminDashboard')->name('admin.dash');
    });

    Route::controller(CategoriesController::class)->group(function(){
        Route::get('/admin/categories', 'CategoriesIndex')->name('category.index');
        Route::post('/admin/categories/store', 'CategoriesStore')->name('category.store');
        Route::get('/admin/categories/edit/{id}', 'CategoriesEdit')->name('category.edit');
        Route::post('/admin/categories/update', 'CategoriesUpdate')->name('category.update');
        Route::get('/admin/categories/delete/{id}', 'CategoriesDelete')->name('category.delete');
        });
        
        Route::controller(ProductsController::class)->group(function(){
            Route::get('/admin/products', 'ProductsIndex')->name('products.index');
            Route::post('/admin/products/store', 'ProductsStore')->name('products.store');
            Route::get('/admin/products/edit/{id}', 'ProductEdit')->name('products.edit');
            Route::post('/admin/products/update', 'ProductUpdate')->name('products.update');
            Route::get('/admin/products/disable/{id}', 'ProductStatus')->name('products.status');
            });
            
    Route::controller(SupplierController::class)->group(function(){
        Route::get('/admin/suppliers', 'SupplierIndex')->name('supplier.index');
        Route::post('/admin/suppliers/store', 'SupplierStore')->name('supplier.store');
        Route::get('/admin/suppliers/edit/{id}', 'SupplierEdit')->name('supplier.edit');
        Route::post('/admin/suppliers/update', 'SupplierUpdate')->name('supplier.update');
        Route::get('/admin/suppliers/change-status/{id}', 'SupplierStatus')->name('supplier.status');
    });
    
    Route::controller(SalesController::class)->group(function(){
        Route::get('/admin/sales', 'SalesIndex')->name('sales.index');
        Route::get('/admin/product/info/{id}', 'getProductData');
        Route::post('/admin/sales/store', 'SaleCompleted')->name('sales.store');
    });

    Route::controller(ReportsController::class)->group(function(){
        Route::get('/admin/reports', 'ReportsIndex')->name('reports.index'); 
        Route::get('/admin/reports/view-invoice/{id}', 'ViewInvoice'); 
        // Route::get('/admin/reports/print-invoice', 'PrintInvoice')->name('print.invoice'); 
    });
});

require __DIR__.'/auth.php';