<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resis', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('master_shipment_id')->nullable()->constrained()->nullOnDelete();
            $table->string('resi_number')->unique();
            $table->string('item_name')->nullable();
            $table->string('customer_name_snapshot')->nullable();
            $table->integer('quantity')->default(1);
            $table->enum('status', [
                'waiting_arrival',
                'arrived_wh_china',
                'in_transit',
                'arrived_indonesia',
                'awaiting_payment',
                'ready_to_ship',
            ])->default('waiting_arrival');
            $table->string('photo_wh_china')->nullable();
            $table->string('photo_arrived_id')->nullable();
            $table->decimal('final_weight_kg', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('resi_number');
            $table->index('user_id');
            $table->index('master_shipment_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resis');
    }
};
