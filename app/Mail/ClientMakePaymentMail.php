<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ClientMakePaymentMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected array $invoiceData)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Client Make Payment',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.client-make-payment',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
