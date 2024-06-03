<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail\Landlord\Customer;

use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Payment;
use App\Models\Landlord\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class PaymentConfirmation extends Mailable {

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
    public function __construct($customer = [], $data = [], $payment = []) {

        $this->data = $data;
        $this->customer = $customer;
        $this->obj = $payment;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = EmailTemplate::on('landlord')->Where('name', 'Payment Confirmation')->first()) {
            return false;
        }

        //validate
        if (!$this->obj instanceof Payment || !$this->customer instanceof Tenant) {
            return false;
        }

        //only active templates
        if ($template->status != 'enabled') {
            return false;
        }


        //get common email variables
        $payload = config('mail.data');

        //set template variables
        $payload += [
            'name' => $this->customer->name,
            'amount' => $this->data['amount'],
            'transaction_id' => $this->obj->transaction_id ?? '---',
        ];

        //save in the database queue
        $queue = new \App\Models\Landlord\EmailQueue();
        $queue->setConnection('landlord');
        $queue->emailqueue_to = $this->customer->email;
        $queue->emailqueue_subject = $template->parse('subject', $payload);
        $queue->emailqueue_message = $template->parse('body', $payload);
        $queue->save();
    }
}
