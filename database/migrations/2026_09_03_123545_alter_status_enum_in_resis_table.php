<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE resis MODIFY COLUMN status ENUM('waiting_arrival', 'arrived_wh_china', 'in_transit', 'arrived_indonesia', 'awaiting_payment', 'ready_to_ship', 'completed') DEFAULT 'waiting_arrival'");
    }

    public function down(): void
    {
        // Reverting this might drop 'completed' records, which isn't safe.
        // We'll leave it as is or revert to previous if possible, but leaving is safer.
        DB::statement("ALTER TABLE resis MODIFY COLUMN status ENUM('waiting_arrival', 'arrived_wh_china', 'in_transit', 'arrived_indonesia', 'awaiting_payment', 'ready_to_ship') DEFAULT 'waiting_arrival'");
    }
};
