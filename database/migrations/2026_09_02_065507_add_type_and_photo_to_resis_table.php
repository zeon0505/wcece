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
        Schema::table('resis', function (Blueprint $table) {
            $table->string('shipment_type')->default('SEA')->after('status');
            $table->string('photo_item')->nullable()->after('shipment_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resis', function (Blueprint $table) {
            $table->dropColumn(['shipment_type', 'photo_item']);
        });
    }
};
