<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\MailTemplates\TemplateMailable;

class StudentRegistrationMail extends TemplateMailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $password;
    public mixed $email;

    public function __construct( array $data)
    {
        $this->password = $data['password'];
        $this->email = $data['email'];
    }
}
