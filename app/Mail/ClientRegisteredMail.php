<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class ClientRegisteredMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public mixed $first_name;

    public mixed $last_name;

    public mixed $email;

    public function __construct(array $data)
    {
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->email = $data['email'];
    }
}
