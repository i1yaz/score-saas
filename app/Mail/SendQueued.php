<?php

/** --------------------------------------------------------------------------------
 * SendQueued
 * Send emails that are stored in the email queue (database)
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Mail;

use App\Models\EmailQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendQueued extends Mailable {
    use Queueable, SerializesModels;

    public $data;

    public $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $attachment = '') {
        //
        $this->data = $data;
        $this->attachment = $attachment;
    }

    /**
     * Nextloop: This will send the email that has been saved in the database (as sent by the cronjob)
     *
     * @return $this
     */
    public function build() {

        //validate
        if (!$this->data instanceof EmailQueue) {
            return;
        }

        //[attachment] send emil with an attachments
        if (is_array($this->attachment)) {
            return $this->from(config('system.email_from_address'), config('system.email_from_name'))
                ->subject($this->data->subject)
                ->with([
                    'content' => $this->data->message,
                ])
                ->view('pages.emails.template')
                ->attach($this->attachment['filepath'], [
                    'as' => $this->attachment['filename'],
                    'mime' => 'application/pdf',
                ]);
        } else {
            //[no attachment] send email without any attahments
            return $this->from(config('system.email_from_address'), config('system.email_from_name'))
                ->subject($this->data->subject)
                ->with([
                    'content' => $this->data->message,
                ])
                ->view('pages.emails.template');
        }
    }
}
