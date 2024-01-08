<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class FlagSessionMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private mixed $Package;

    public function __construct(protected array $session)
    {
        $this->Package = $session['package'];

    }
}
