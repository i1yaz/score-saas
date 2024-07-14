<?php

namespace App\Mail\Landlord\Admin;

use App\Models\Landlord\EmailQueue;
use App\Models\Landlord\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class TestEmail extends Mailable {
    use Queueable;

    /**
     * The data for merging into the email
     */
    public $data;

    /**
     * Model instance
     */
    public $obj;


    public $emailerrepo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data = []) {

        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        if (!$template = EmailTemplate::Where('name', 'System Notification')->first()) {
            return false;
        }

        $payload = config('mail.data');
        $payload += [
            'notification_subject' => $this->data['notification_subject'],
            'notification_title' => $this->data['notification_title'],
            'notification_message' => $this->data['notification_message'],
            'first_name' => $this->data['first_name'],
        ];

        $queue = new EmailQueue();
        $queue->to = $this->data['email'];
        $queue->subject = $template->parse('subject', $payload);
        $queue->message = $template->parse('body', $payload);
        $queue->save();
    }
}
