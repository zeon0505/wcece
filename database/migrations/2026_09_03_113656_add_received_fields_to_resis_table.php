<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resis', function (Blueprint $table) {
            $table->string('photo_received')->nullable()->after('photo_arrived_id');
            $table->timestamp('received_at')->nullable()->after('photo_received');
        });
    }

    public function down(): void
    {
        Schema::table('resis', function (Blueprint $table) {
            $table->dropColumn(['photo_received', 'received_at']);
        });
    }
};
