<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
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
    });
});

require __DIR__.'/auth.php';