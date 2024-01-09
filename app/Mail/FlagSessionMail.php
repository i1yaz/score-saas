<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class FlagSessionMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private mixed $package;

    public function __construct(protected array $session)
    {
        $this->package = $session['package'];

    }
}
