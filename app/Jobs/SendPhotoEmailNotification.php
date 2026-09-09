<?php

namespace App\Jobs;

use App\Mail\ResiStatusUpdatedMail;
use App\Models\User;
use App\Models\Resi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendPhotoEmailNotification
{
    use Queueable;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(
        public readonly int $resiId,
        public readonly string $type
    ) {}

    public function handle(): void
    {
        $resi = Resi::with(['user', 'statusHistories'])->find($this->resiId);

        if (! $resi) {
            return;
        }

        $emails = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->unique();

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ResiStatusUpdatedMail($resi, $this->type));
            } catch (\Throwable $e) {
                Log::error("Failed to send photo notification email to {$email}: " . $e->getMessage());
            }
        }
    }
}
