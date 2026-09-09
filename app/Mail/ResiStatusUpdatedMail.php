<?php

namespace App\Mail;

use App\Models\Resi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResiStatusUpdatedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Resi $resi,
        public readonly ?string $uploadedPhotoType = null
    ) {}

    public function envelope(): Envelope
    {
        if ($this->uploadedPhotoType === 'wh_china') {
            $subject = '[WH CE] Foto Paket Tiba Gudang China - ' . $this->resi->resi_number;
        } elseif ($this->uploadedPhotoType === 'arrived_id') {
            $subject = '[WH CE] Foto Paket Tiba Indonesia - ' . $this->resi->resi_number;
        } else {
            $subject = '[WH CE] Update Status Paket ' . $this->resi->resi_number;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.resi-status-updated',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->uploadedPhotoType === 'wh_china' && $this->resi->photo_wh_china) {
            $filePath = storage_path('app/public/' . $this->resi->photo_wh_china);
            if (file_exists($filePath)) {
                $attachments[] = Attachment::fromPath($filePath)
                    ->as('foto_gudang_china_' . $this->resi->resi_number . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
            }
        } elseif ($this->uploadedPhotoType === 'arrived_id' && $this->resi->photo_arrived_id) {
            $filePath = storage_path('app/public/' . $this->resi->photo_arrived_id);
            if (file_exists($filePath)) {
                $attachments[] = Attachment::fromPath($filePath)
                    ->as('foto_tiba_indonesia_' . $this->resi->resi_number . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
            }
        }

        return $attachments;
    }
}
