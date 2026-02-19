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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->string('customer_name')->nullable();
            $table->float('total_amount');
            $table->float('total_profit');
            $table->string('payment_type');
            $table->float('amount_paid');
            $table->float('change_amount');
            $table->enum('status', ['pending', 'completed', 'cancelled']);
            $table->timestamp('completed_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};