<?php

namespace App\Mail;

use App\Models\StudentTutoringPackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentInvoiceMailAfterStudentTutoringPackageCreation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public StudentTutoringPackage $studentTutoringPackage)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Parent Invoice After Student Tutoring Package Creation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.parent-invoice-mail-after-student-tutoring-package-creation',
            with: [
                'studentTutoringPackage' => $this->studentTutoringPackage,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
