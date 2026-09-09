<?php

namespace App\Mail;

use App\Models\MasterShipment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BoxPhotoNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly MasterShipment $shipment,
        public readonly User $user,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📦 Foto Box Pengirimanmu Sudah Tersedia — ' . ($this->shipment->name ?: $this->shipment->code),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.box-photo-notification',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
