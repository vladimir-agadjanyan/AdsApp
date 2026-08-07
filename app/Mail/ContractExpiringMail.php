<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContractExpiringMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly Contract $contract, public readonly int $days) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "До окончания договора №{$this->contract->number} осталось {$this->days} дней",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contracts.expiring',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
