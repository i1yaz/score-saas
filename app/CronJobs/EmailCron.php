<?php

/** ---------------------------------------------------------------------------------------------------
 * Email Cron
 * Send emails that are stored in the email queue (database)
 * This cronjob is envoked by by the task scheduler which is in 'application/app/Console/Kernel.php'
 *      - the scheduler is set to run this every minuted
 *      - the schedler itself is evoked by the signle cronjob set in cpanel (which runs every minute)
 * @package    Grow CRM | Nulled By raz0r
 * @author     NextLoop
 *-----------------------------------------------------------------------------------------------------*/

namespace App\CronJobs;
use App\Mail\SendQueued;
use App\Models\EmailQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\Multitenancy\Models\Tenant;

class EmailCron {

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
         */
        $limit = 20;
        if ($emails = EmailQueue::Where('type', 'general')->where('status', 'new')->take($limit)->get()) {
            // Optimize:: can be done in one query mark all emails in the batch as processing - to avoid batch duplicates/collisions
            foreach ($emails as $email) {
                $email->update([
                    'status' => 'processing',
                    'started_at' => now(),
                ]);
            }

            //now process
            foreach ($emails as $email) {

                if ( filter_var($email->to, FILTER_VALIDATE_EMAIL) ) {
                    Mail::to($email->to)->send(new SendQueued($email));
                    //log email
                    $log = new \App\Models\EmailLog();
                    $log->email = $email->to;
                    $log->subject = $email->subject;
                    $log->body = $email->message;
                    $log->save();
                }
                EmailQueue::Where('id', $email->id)->delete();
            }

            \App\Models\Setting::where('id', 1)
                ->update([
                    'cronjob_has_run' => 'yes',
                    'cronjob_last_run' => now(),
                ]);
        }

    }
}
