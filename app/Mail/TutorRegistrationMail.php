<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TutorRegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tutor Registration',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tutor-registration',
            with: [
                'password' => $this->data['password']
            ]
        );

    }

    public function attachments(): array
    {
        return [];
    }
}
