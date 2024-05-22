<?php

namespace App\CronJobs\Emails;
use App\Mail\Landlord\SendQueued;
use App\Models\Landlord\EmailLog;
use App\Models\Landlord\EmailQueue;
use Illuminate\Support\Facades\Mail;
use Spatie\Multitenancy\Models\Tenant;

class EmailCron {

    public function __invoke() {

        if (Tenant::current()) {
            return;
        }

        /**
         * Send emails
         *   These emails are being sent every minute. You can set a higher or lower sending limit.
         */
        $limit = 20;
        if ($emails = EmailQueue::On('landlord')->Where('type', 'general')->where('status', 'new')->take($limit)->get()) {

            // Optimize:: can be done in one query mark all emails in the batch as processing - to avoid batch duplicates/collisions
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
                    Mail::to($email->to)->send(new SendQueued($email));
                    //log email
                    $log = new EmailLog();
                    $log->setConnection('landlord');
                    $log->email = $email->to;
                    $log->subject = $email->subject;
                    $log->body = $email->message;
                    $log->save();

                }
                //delete email from the queue
                EmailQueue::On('landlord')->Where('id', $email->id)->delete();
            }
        }

        //reset last cron run data
        \App\Models\Setting::On('landlord')->Where('id', 'default')
            ->update([
                'cronjob_has_run' => 'yes',
                'cronjob_last_run' => now(),
            ]);

    }
}
