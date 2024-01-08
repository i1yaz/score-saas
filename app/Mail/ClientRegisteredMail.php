<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class ClientRegisteredMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public mixed $FirstName;
    public mixed $LastName;
    public mixed $Email;

    public function __construct(array $data)
    {
        $this->FirstName = $data['first_name'];
        $this->LastName = $data['last_name'];
        $this->Email = $data['email'];
    }
}
