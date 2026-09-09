<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_shipments', function (Blueprint $table) {
            $table->string('photo_box')->nullable()->after('notes');
            $table->timestamp('photo_box_uploaded_at')->nullable()->after('photo_box');
        });
    }

    public function down(): void
    {
        Schema::table('master_shipments', function (Blueprint $table) {
            $table->dropColumn(['photo_box', 'photo_box_uploaded_at']);
        });
    }
};
