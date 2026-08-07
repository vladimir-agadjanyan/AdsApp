<?php

namespace App\Mail;

use App\Models\AdvertisingObject;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MissingPhotoReportMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly AdvertisingObject $advertisingObject,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Отсутствует фотоотчет по объекту {$this->advertisingObject->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.photo-reports.missing',
        );
    }
}