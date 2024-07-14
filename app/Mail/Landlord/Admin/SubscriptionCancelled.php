<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 *----------------------------------------------------------------------------------*/

namespace App\Mail\Landlord\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class SubscriptionCancelled extends Mailable {
    use Queueable;

    /**
     * The data for merging into the email
     */
    public $data;

    /**
     * Model instance
     */
    public $obj;

    /**
     * Model instance
     */
    public $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer = [], $data = [], $obj = []) {

        $this->data = $data;
        $this->customer = $customer;
        $this->obj = $obj;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = \App\Models\Landlord\EmailTemplate::on('landlord')
            ->Where('name', 'Subscription Cancelled')
            ->Where('type', 'customer')->first()) {
            return false;
        }

        if (!$this->customer instanceof \App\Models\Landlord\Tenant) {
            return false;
        }

        if ($template->status != 'enabled') {
            return false;
        }

        //get common email variables
        $payload = config('mail.data');

        //set template variables
        $payload += [
            'name' => $this->customer->name,
            'customer_url' => $this->data['customer_url'] ?? '',
            'plan_name' => $this->data['plan_name'] ?? '---',
            'subscription_id' => $this->data['subscription_id'] ?? '---',
            'subscription_amount' => $this->data['subscription_amount'] ?? '---',
        ];

        //save in the database queue
        $queue = new \App\Models\Landlord\EmailQueue();
        $queue->setConnection('landlord');
        $queue->to = $this->customer->email;
        $queue->subject = $template->parse('subject', $payload);
        $queue->message = $template->parse('body', $payload);
        $queue->save();
    }
}
