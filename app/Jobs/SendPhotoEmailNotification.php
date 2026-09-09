<?php

namespace App\Jobs;

use App\Mail\ResiStatusUpdatedMail;
use App\Models\Resi;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPhotoEmailNotification
{
    public function __construct(
        public readonly int $resiId,
        public readonly string $type
    ) {}

    public function handle(): void
    {
        $resi = Resi::with(['user'])->find($this->resiId);

        if (! $resi) {
            return;
        }

        $emails = [];
        if ($resi->user && ! empty($resi->user->email)) {
            $emails[] = $resi->user->email;
        } else {
            $emails = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->unique()->toArray();
        }

        if (empty($emails)) {
            return;
        }

        $primary = array_shift($emails);

        try {
            $mailable = new ResiStatusUpdatedMail($resi, $this->type);
            if (! empty($emails)) {
                Mail::to($primary)->bcc($emails)->send($mailable);
            } else {
                Mail::to($primary)->send($mailable);
            }
        } catch (\Throwable $e) {
            Log::error("Failed to send photo notification email for resi {$this->resiId}: " . $e->getMessage());
        }
    }
}
