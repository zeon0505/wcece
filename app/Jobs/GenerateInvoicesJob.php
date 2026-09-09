<?php

namespace App\Jobs;

use App\Mail\InvoiceGeneratedMail;
use App\Models\Invoice;
use App\Models\MasterShipment;
use App\Models\Resi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class GenerateInvoicesJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $shipmentId) {}

    public function handle(): void
    {
        $shipment = MasterShipment::with('resis.user')->findOrFail($this->shipmentId);

        // Group resis by user
        $resisByUser = $shipment->resis->groupBy('user_id');

        foreach ($resisByUser as $userId => $userResis) {
            if (! $userId) {
                continue; // Skip unmatched resis
            }

            $totalWeight = $userResis->sum('final_weight_gram');
            $handlingFee = $userResis->sum(fn ($r) => $r->fee_wh !== null && $r->fee_wh >= 0 ? $r->fee_wh : $shipment->handling_fee);
            $packingFee = $userResis->sum(fn ($r) => $r->packing !== null && $r->packing >= 0 ? $r->packing : $shipment->packing_fee);
            $ratePerGram = $shipment->rate_per_gram;
            $totalAmount = ($totalWeight * $ratePerGram) + $handlingFee + $packingFee;

            DB::transaction(function () use ($shipment, $userId, $totalWeight, $ratePerGram, $handlingFee, $packingFee, $totalAmount, $userResis): void {
                $invoice = Invoice::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'master_shipment_id' => $shipment->id,
                    ],
                    [
                        'invoice_number' => Invoice::generateNumber($shipment),
                        'total_weight_gram' => $totalWeight,
                        'rate_per_gram' => $ratePerGram,
                        'handling_fee' => $handlingFee,
                        'packing_fee' => $packingFee,
                        'total_amount' => $totalAmount,
                        'status' => 'unpaid',
                        'due_date' => now()->addDays(7),
                    ]
                );

                // Transition resis to awaiting_payment
                foreach ($userResis as $resi) {
                    if ($resi->status === 'arrived_indonesia') {
                        $resi->transitionTo('awaiting_payment');
                    }
                }

                $user = $userResis->first()->user;
                Mail::to($user->email)->queue(new InvoiceGeneratedMail($invoice));
            });
        }
    }
}
