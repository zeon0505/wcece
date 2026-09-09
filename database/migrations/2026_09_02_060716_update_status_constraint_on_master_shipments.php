<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_shipments', function (Blueprint $table) {
            $table->dropIndex('master_shipments_status_index');
        });

        Schema::table('master_shipments', function (Blueprint $table) {
            $table->string('status_new')->default('in_transit');
        });

        DB::statement('UPDATE master_shipments SET status_new = status');

        Schema::table('master_shipments', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('master_shipments', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
            $table->index('status');
        });
    }

    public function down(): void
    {
        //
    }
};
