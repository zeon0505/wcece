<?php

namespace App\Jobs;

use App\Mail\BoxPhotoNotificationMail;
use App\Models\MasterShipment;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBoxPhotoEmailNotification
{
    public function __construct(public readonly int $shipmentId) {}

    public function handle(): void
    {
        $shipment = MasterShipment::find($this->shipmentId);

        if (! $shipment) {
            return;
        }

        $emails = User::whereHas('resis', function ($q) {
            $q->where('master_shipment_id', $this->shipmentId);
        })->whereNotNull('email')->where('email', '!=', '')->pluck('email')->unique()->toArray();

        if (empty($emails)) {
            $emails = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->unique()->toArray();
        }

        if (empty($emails)) {
            return;
        }

        $primary = array_shift($emails);
        $userObj = User::where('email', $primary)->first() ?? new User(['name' => 'Customer']);

        try {
            $mailable = new BoxPhotoNotificationMail($shipment, $userObj);
            if (! empty($emails)) {
                Mail::to($primary)->bcc($emails)->send($mailable);
            } else {
                Mail::to($primary)->send($mailable);
            }
        } catch (\Throwable $e) {
            Log::error('BoxPhoto mail failed for shipment ' . $this->shipmentId . ': ' . $e->getMessage());
        }
    }
}
