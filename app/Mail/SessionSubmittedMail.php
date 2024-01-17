<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class SessionSubmittedMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public mixed $time;
    public mixed $student_email;
    public mixed $student_first_name;
    public mixed $student_last_name;
    public mixed $bill_amount;
    private mixed $payment_charge_date;

    public function __construct(array $data)
    {
        $this->time = $data['time'];
        $this->student_email = $data['student_email'];
        $this->student_first_name = $data['student_first_name'];
        $this->student_last_name = $data['student_last_name'];
        $this->bill_amount = $data['bill_amount'];
        $this->payment_charge_date = $data['start_time'];
    }

}
