<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\MailTemplates\Models\MailTemplate;

class MailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        MailTemplate::create([
            'mailable' => \App\Mail\ClientMakePaymentMail::class,
            'name' => 'Client made Payment',
            'description' => 'Send email when client make payment for non-invoice package',
            'subject' => 'Client made Payment',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\ClientRegisteredMail::class,
            'name' => 'Client Registered',
            'description' => 'Send email when client is registered',
            'subject' => 'Client Registered',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\FlagSessionMail::class,
            'name' => 'Flag session',
            'description' => 'Send email to admin when a session is flagged by a tutor',
            'subject' => 'Flag session',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\ParentInvoiceMailAfterMonthlyInvoicePackageCreationMail::class,
            'name' => 'Invoice for monthly package',
            'description' => 'Send email when monthly package is created for a student',
            'subject' => 'Invoice for monthly package',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\ParentInvoiceMailAfterStudentTutoringPackageCreation::class,
            'name' => 'Invoice for Student Tutoring Package',
            'description' => 'Send email when student tutoring package is created for a student',
            'subject' => 'Invoice for Student Tutoring Package',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\ParentRegisteredMail::class,
            'name' => 'Parent Registered',
            'description' => 'Send email when parent is registered',
            'subject' => 'Parent Registered',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\StudentRegistrationMail::class,
            'name' => 'Student Registered',
            'description' => 'Send email when student is registered',
            'subject' => 'Student Registered',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\TutorRegistrationMail::class,
            'name' => 'Tutor Registered',
            'description' => 'Send email when tutor is registered',
            'subject' => 'Tutor Registered',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
        MailTemplate::create([
            'mailable' => \App\Mail\SessionSubmittedMail::class,
            'name' => 'Session Submitted',
            'description' => 'Send email when session is submitted.',
            'subject' => 'Session Submitted',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
    }
}
