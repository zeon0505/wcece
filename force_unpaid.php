<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$inv = App\Models\Invoice::find(1);
if($inv) {
    $inv->status = 'unpaid';
    $inv->save();
    
    $inv->masterShipment->resis()->update(['status' => 'awaiting_payment']);
    App\Models\Payment::where('invoice_id', 1)->delete();
    
    echo "Reset Invoice 1 to UNPAID and Resis to AWAITING PAYMENT\n";
} else {
    echo "Invoice 1 not found\n";
}
