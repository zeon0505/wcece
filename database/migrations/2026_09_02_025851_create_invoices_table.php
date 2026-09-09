<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('master_shipment_id')->constrained();
            $table->decimal('total_weight_kg', 8, 2);
            $table->decimal('rate_per_kg', 10, 2);
            $table->decimal('handling_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['unpaid', 'pending_verification', 'paid', 'failed'])->default('unpaid');
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
            $table->index('master_shipment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
