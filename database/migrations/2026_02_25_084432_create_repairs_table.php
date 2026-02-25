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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            $table->string('repair_no')->nullable();
            $table->string('customer_name');
            $table->string('contact_number');
            $table->string('device_type');
            $table->string('device_brand');
            $table->text('issue_description');
            $table->text('diagnosis')->nullable();
            $table->float('labor_fee')->nullable();
            $table->float('total_parts_amount')->nullable();
            $table->float('total_amount')->nullable();
            $table->enum('status', ['pending_diagnosis', 'awaiting_approval', 'in_progress', 'completed', 'released', 'cancelled', 'abandoned']);
            $table->integer('sale_id')->nullable();
            $table->date('pickup_deadline')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('released_at')->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};