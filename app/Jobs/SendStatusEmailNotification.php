<?php

namespace App\Jobs;

use App\Mail\ResiStatusUpdatedMail;
use App\Models\Resi;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendStatusEmailNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly int $resiId) {}

    public function handle(): void
    {
        $resi = Resi::with(['user', 'statusHistories'])->find($this->resiId);

        if (! $resi || ! $resi->user) {
            return;
        }

        Mail::to($resi->user->email)->send(new ResiStatusUpdatedMail($resi));
    }
}
