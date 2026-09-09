<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$inv = App\Models\Invoice::find(1);
if($inv) {
    $inv->status = 'paid';
    $inv->save();
    
    $inv->masterShipment->resis()->update(['status' => 'ready_to_ship']);
    echo "Forced Invoice 1 to PAID and Resis to READY TO SHIP\n";
} else {
    echo "Invoice 1 not found\n";
}
