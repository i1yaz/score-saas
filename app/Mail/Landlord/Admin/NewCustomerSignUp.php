<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail\Landlord\Admin;

use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Package;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class NewCustomerSignUp extends Mailable {
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

    public $emailerrepo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user = [], $data = [], $obj = []) {

        $this->data = $data;
        $this->user = $user;
        $this->obj = $obj;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        //email template
        if (!$template = EmailTemplate::on('landlord')->Where('name', 'New Customer Sign Up')->first()) {
            return false;
        }

        //validate
        if (!$this->obj instanceof Package || !$this->user instanceof \App\Models\User) {
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
            'account_url' => 'https://' . $this->data['account_url'],
            'account_name' => $this->data['account_name'],
            'plan_name' => $this->obj->package_name,
            'button_url' => url('customers/' . $this->data['customer_id']),
        ];

        //save in the database queue
        $queue = new \App\Models\Landlord\EmailQueue();
        $queue->setConnection('landlord');
        $queue->to = $this->user->email;
        $queue->subject = $template->parse('subject', $payload);
        $queue->message = $template->parse('body', $payload);
        $queue->save();
    }
}
