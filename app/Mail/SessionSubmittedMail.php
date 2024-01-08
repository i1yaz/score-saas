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

    public function __construct()
    {
    }

}
