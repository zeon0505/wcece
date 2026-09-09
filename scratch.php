<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$collection = \Maatwebsite\Excel\Facades\Excel::toCollection(new \App\Imports\ResiImportPreview, __DIR__.'/storage/app/public/test.xlsx');
print_r($collection->first()->take(3)->toArray());
