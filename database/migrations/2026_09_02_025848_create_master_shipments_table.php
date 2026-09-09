<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_shipments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('code')->unique();
            $table->enum('status', ['in_transit', 'arrived_indonesia', 'completed'])->default('in_transit');
            $table->decimal('rate_per_kg', 10, 2)->nullable();
            $table->decimal('handling_fee', 10, 2)->default(0);
            $table->timestamp('departed_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index('status');
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_shipments');
    }
};
