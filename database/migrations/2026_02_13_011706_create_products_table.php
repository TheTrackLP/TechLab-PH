<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->foreignId('supplier_id')->constrained();
            $table->string('brand')->nullable();
            $table->string('image')->nullable();
            $table->string('name');
            $table->integer('sku')->nullable();
            $table->longText('description')->nullable();
            $table->integer('stock_quantity');
            $table->integer('minimum_stock');
            $table->float('cost_price');
            $table->float('selling_price');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};