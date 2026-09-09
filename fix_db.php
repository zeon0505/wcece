<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

DB::statement('UPDATE master_shipments SET status_new = status');

Schema::table('master_shipments', function (Blueprint $table) {
    $table->dropColumn('status');
});

Schema::table('master_shipments', function (Blueprint $table) {
    $table->renameColumn('status_new', 'status');
    $table->index('status');
});

DB::table('migrations')->insert(['migration' => '2026_09_02_060716_update_status_constraint_on_master_shipments', 'batch' => 2]);
echo "Done\n";
