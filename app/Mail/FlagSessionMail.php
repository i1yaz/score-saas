<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlagSessionMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected array $session)
    {

    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Flag Session',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.flag-session',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
