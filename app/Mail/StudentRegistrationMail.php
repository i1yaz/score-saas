<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class StudentRegistrationMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct( array $data)
    {
    }
}
