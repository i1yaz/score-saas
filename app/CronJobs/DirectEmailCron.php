<?php

/** ------------------------------------------------------------------------------------------------------------------
 * Email Cron
 * Send emails that were composed in the CRM (e.g. client email)  emails that are stored in the email queue (database)
 * This cronjob is invoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 *      - the scheduler is set to run this every minuted
 *      - the scheduler itself is invoked by the single cronjob set in cpanel (which runs every minute)
 * @author     NextLoop
 *-------------------------------------------------------------------------------------------------------------------*/

namespace App\CronJobs;

use App\Mail\DirectEmail;
use Illuminate\Support\Facades\Mail;
use Log;
use Spatie\Multitenancy\Models\Tenant;

class DirectEmailCron {

    public function __invoke() {
        if (Tenant::current() == null) {
            return;
        }
          //boot system settings
          middlwareBootSystem();
          middlewareBootMail();
        /**
         * Send emails
         *   These emails are being sent every minute. You can set a higher or lower sending limit.
         *   Just note that if there are attachments, a high limit can cause server timeouts
         */
        $limit = 5;
        if ($emails = \App\Models\EmailQueue::Where('type', 'direct')->where('status', 'new')->take($limit)->get()) {

            //log that its run
            Log::info("some emails were found", ['process' => '[cronjob][email-processing]', config('app.debug_ref'), 'function' => __function__, 'file' => basename(__FILE__), 'line' => __line__, 'path' => __file__, 'payload' => $emails]);

            //mark all emails in the batch as processing - to avoid batch duplicates/collisions
            foreach ($emails as $email) {
                $email->update([
                    'status' => 'processing',
                    'started_at' => now(),
                ]);
            }

            //now process
            foreach ($emails as $email) {

                //send the email (only to a valid email address)
                if ($email->to != '') {

                    /** ----------------------------------------------
                     * send email to client
                     * ----------------------------------------------*/
                    $data = [
                        'email_to' => $email->to,
                        'email_subject' => $email->subject,
                        'email_body' => $email->message,
                        'from_email' => $email->from_email,
                        'from_name' => $email->from_name,
                    ];

                    //add any attachments
                    if ($email->attachments != '') {
                        $attachments = json_decode($email->attachments, true);
                        if (is_array($attachments)) {
                            $data['attachments'] = $attachments;
                        }
                    }
                    Mail::to($email->to)->send(new DirectEmail($data));

                    //log that we sent email
                    $log = new \App\Models\EmailLog();
                    $log->email = $email->to;
                    $log->subject = $email->subject;
                    $log->body = $email->message;
                    $log->save();

                }
                //delete email from the queue
                \App\Models\EmailQueue::Where('id', $email->id)->delete();
            }
        }
    }
}
