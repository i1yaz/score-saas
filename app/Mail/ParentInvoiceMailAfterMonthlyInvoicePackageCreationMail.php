<?php

namespace App\Mail;

use App\Models\MonthlyInvoicePackage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public  $monthlyInvoicePackage)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Parent Invoice Mail After Monthly Tutoring Package Creation',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.parent-invoice-mail-after-monthly-tutoring-package-creation',
            with: [
                'studentTutoringPackage' => $this->monthlyInvoicePackage,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
