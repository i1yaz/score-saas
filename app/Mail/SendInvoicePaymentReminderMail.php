<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class SendInvoicePaymentReminderMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $invoiceable_type;
    public $due_date;
    public $price;
    public $student_email;
    public $student_first_name;
    public $student_last_name;
    public $parent_email;
    public $parent_first_name;
    public $parent_last_name;
    public $client_email;
    public $client_first_name;
    public $client_last_name;

    public function __construct(array $invoice)
    {
        $this->invoiceable_type = $invoice['invoiceable_type'];
        $this->due_date = $invoice['due_date'];
        $this->price = $invoice['price'];
        $this->student_email = $invoice['student_email'];
        $this->student_first_name = $invoice['student_first_name'];
        $this->student_last_name = $invoice['student_last_name'];
        $this->parent_email = $invoice['parent_email'];
        $this->parent_first_name = $invoice['parent_first_name'];
        $this->parent_last_name = $invoice['parent_last_name'];
        $this->client_email = $invoice['client_email'];
        $this->client_first_name = $invoice['client_first_name'];
        $this->client_last_name = $invoice['client_last_name'];
    }
}
