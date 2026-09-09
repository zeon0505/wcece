<?php

namespace App\Jobs;

use App\Mail\BoxPhotoNotificationMail;
use App\Models\MasterShipment;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendBoxPhotoEmailNotification
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(public readonly int $shipmentId) {}

    public function handle(): void
    {
        $shipment = MasterShipment::find($this->shipmentId);

        if (! $shipment) {
            return;
        }

        $users = User::whereNotNull('email')->where('email', '!=', '')->get();

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new BoxPhotoNotificationMail($shipment, $user));
            } catch (\Throwable $e) {
                Log::error('BoxPhoto mail failed for ' . $user->email . ': ' . $e->getMessage());
            }
        }
    }
}
