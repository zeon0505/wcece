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
        Schema::table('resi_claims', function (Blueprint $table) {
            $table->string('line_name')->nullable()->after('pickup_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resi_claims', function (Blueprint $table) {
            $table->dropColumn('line_name');
        });
    }
};
