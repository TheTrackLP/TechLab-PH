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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id');
            $table->string('return_no');
            $table->enum('return_type', ['refund', 'exhange']);
            $table->float('total_amount');
            $table->enum('reason', ['defective', 'wrong_item', 'damaged', 'changed_item']);
            $table->text('notes')->nullable();
            $table->integer('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};