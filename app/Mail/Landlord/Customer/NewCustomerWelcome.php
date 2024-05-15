<?php

/** --------------------------------------------------------------------------------
 * [template]
 * This classes renders the [new email] email and stores it in the queue
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail\Landlord\Customer;

use App\Models\Landlord\EmailQueue;
use App\Models\Landlord\EmailTemplate;
use App\Models\Landlord\Package;
use App\Models\Landlord\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class NewCustomerWelcome extends Mailable {
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
        if (!$template = EmailTemplate::on('landlord')->Where('name', 'New Customer Welcome')->first()) {
            return false;
        }

        //validate
        if (!$this->obj instanceof Package || !$this->customer instanceof Tenant) {
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
            'name' => $this->customer->tenant_name,
            'account_url' => $this->data['account_url'],
            'account_name' => $this->data['account_name'],
            'password' => $this->data['password'],
            'username' => $this->customer->tenant_email,
            'plan_name' => $this->obj->package_name,
            'button_url' => $this->data['account_url'],
        ];

        //save in the database queue
        $queue = new EmailQueue();
        $queue->setConnection('landlord');
        $queue->to = $this->customer->tenant_email;
        $queue->subject = $template->parse('subject', $payload);
        $queue->message = $template->parse('body', $payload);
        $queue->save();
    }
}
