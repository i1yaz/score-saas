<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail\Landlord\Admin;

use App\Models\Landlord\EmailQueue;
use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class NewPayment extends Mailable {

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
    public $user;

    /**
     * System config
     */
    public $config;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = [], $data = [], $payment = []) {

        $this->data = $data;
        $this->user = $user;
        $this->obj = $payment;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = EmailTemplate::on('landlord')->Where('name', 'New Payment')->first()) {
            return false;
        }

        //validate
        if (!$this->obj instanceof Payment || !$this->user instanceof User) {
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
            'name' => $this->user->first_name,
            'customer_name' => $this->data['customer_name'],
            'customer_id' => $this->data['customer_id'],
            'amount' => $this->data['amount'],
            'payment_gateway' => $this->data['payment_gateway'],
            'transaction_id' => $this->obj->transaction_id,
        ];

        //save in the database queue
        $queue = new EmailQueue();
        $queue->setConnection('landlord');
        $queue->to = $this->user->email;
        $queue->subject = $template->parse('subject', $payload);
        $queue->message = $template->parse('body', $payload);
        $queue->save();
    }
}
