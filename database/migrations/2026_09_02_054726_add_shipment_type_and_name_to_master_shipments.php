<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_shipments', function (Blueprint $table) {
            $table->string('name')->nullable()->after('code');             // e.g. "Box 1", "Box 2"
            $table->string('shipment_type', 10)->default('SEA')->after('name'); // SEA or AIR
            $table->text('notes')->nullable()->after('shipment_type');
        });
    }

    public function down(): void
    {
        Schema::table('master_shipments', function (Blueprint $table) {
            $table->dropColumn(['name', 'shipment_type', 'notes']);
        });
    }
};
