<?php

namespace App\Jobs;

use App\Mail\ResiStatusUpdatedMail;
use App\Models\Resi;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendStatusEmailNotification
{
    use Queueable;

    public function __construct(public readonly int $resiId) {}

    public function handle(): void
    {
        $resi = Resi::with(['user', 'statusHistories'])->find($this->resiId);

        if (! $resi) {
            return;
        }

        // Send notification to resi owner (if exists) and all registered users
        $emails = User::whereNotNull('email')->where('email', '!=', '')->pluck('email')->unique();

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new ResiStatusUpdatedMail($resi));
            } catch (\Throwable $e) {
                Log::error("Failed to send status notification email to {$email}: " . $e->getMessage());
            }
        }
    }
}
