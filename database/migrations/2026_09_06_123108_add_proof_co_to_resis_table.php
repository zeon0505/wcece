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
            $table->string('proof_co')->nullable()->after('photo_item');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resis', function (Blueprint $table) {
            $table->dropColumn('proof_co');
        });
    }
};
