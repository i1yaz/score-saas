<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class FlagSessionMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public mixed $package = 334445;

    public function __construct(array $session)
    {
        $this->package = $session['package'];

    }
}
