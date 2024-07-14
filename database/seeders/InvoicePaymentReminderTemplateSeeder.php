<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\MailTemplates\Models\MailTemplate;

class InvoicePaymentReminderTemplateSeeder extends Seeder
{
    public function run(): void
    {
        MailTemplate::create([
            'mailable' => \App\Mail\SendInvoicePaymentReminderMail::class,
            'name' => 'Send Invoice Payment Reminder',
            'description' => 'Send email to remind client for payment of invoice daily if the due date is in one week.',
            'subject' => 'Send Invoice Payment Reminder',
            'html_template' => '<h1>Hello!</h1>',
            'text_template' => 'Hello!',
        ]);
    }
}
