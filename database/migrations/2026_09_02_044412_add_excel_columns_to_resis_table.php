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
            $table->decimal('weight', 10, 2)->nullable()->after('quantity');
            $table->decimal('rate', 15, 2)->nullable()->after('weight');
            $table->decimal('cargo_tax', 15, 2)->nullable()->after('rate');
            $table->decimal('fee_wh', 15, 2)->nullable()->after('cargo_tax');
            $table->decimal('packing', 15, 2)->nullable()->after('fee_wh');
            $table->decimal('total', 15, 2)->nullable()->after('packing');
            $table->decimal('paid', 15, 2)->nullable()->after('total');
            $table->boolean('shipped')->default(false)->after('paid');
        });
    }

    public function down(): void
    {
        Schema::table('resis', function (Blueprint $table) {
            $table->dropColumn([
                'weight',
                'rate',
                'cargo_tax',
                'fee_wh',
                'packing',
                'total',
                'paid',
                'shipped'
            ]);
        });
    }
};
