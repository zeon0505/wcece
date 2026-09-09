<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename columns
        Schema::table('resis', function (Blueprint $table) {
            $table->renameColumn('final_weight_kg', 'final_weight_gram');
        });

        Schema::table('master_shipments', function (Blueprint $table) {
            $table->renameColumn('rate_per_kg', 'rate_per_gram');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('total_weight_kg', 'total_weight_gram');
            $table->renameColumn('rate_per_kg', 'rate_per_gram');
        });

        // 2. Convert existing values (kg to gram)
        // Multiply by 1000
        DB::table('resis')->update([
            'final_weight_gram' => DB::raw('final_weight_gram * 1000')
        ]);
        
        DB::table('invoices')->update([
            'total_weight_gram' => DB::raw('total_weight_gram * 1000')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert data
        DB::table('resis')->update([
            'final_weight_gram' => DB::raw('final_weight_gram / 1000')
        ]);
        
        DB::table('invoices')->update([
            'total_weight_gram' => DB::raw('total_weight_gram / 1000')
        ]);

        // Revert columns
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('total_weight_gram', 'total_weight_kg');
            $table->renameColumn('rate_per_gram', 'rate_per_kg');
        });

        Schema::table('master_shipments', function (Blueprint $table) {
            $table->renameColumn('rate_per_gram', 'rate_per_kg');
        });

        Schema::table('resis', function (Blueprint $table) {
            $table->renameColumn('final_weight_gram', 'final_weight_kg');
        });
    }
};
