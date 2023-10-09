<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PartialCompletionCodeRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $scheduledDate = request()->scheduled_date;
        $startTime = request()->start_time;
        $endTime = request()->end_time;
        $startTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $startTime");
        $endTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $endTime");

        $attendedStartTime = request()->attended_start_time;
        $attendedEndTime = request()->attended_end_time;
        $chargeMissedStartTime = request()->charge_missed_start_time;
        $chargeMissedEndTime = request()->charge_missed_end_time;

        if ((integer)request()->session_completion_code === 2){
            if ($attribute == 'attended_start_time') {
                if ($attendedStartTime) {
                    $attendedStartTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $attendedStartTime");
                    if ($attendedStartTime->isBefore($startTime)) {
                        $fail('Attended start time must be greater than session start time');
                    }
                }
            }
            if ($attribute == 'attended_end_time') {
                if ($attendedEndTime) {
                    $attendedEndTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $attendedEndTime");
                    if ($attendedEndTime->isAfter($endTime)) {
                        $fail('Attended end time must be less than session end time');
                    }
                }
            }
        }

        if ((integer)request()->charge_for_missed_time===2){
            if ($attribute == 'charge_missed_start_time') {
                if ($chargeMissedStartTime) {
                    $chargeMissedStartTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $chargeMissedStartTime");
                    if ($chargeMissedStartTime->isAfter($endTime)) {
                        $fail('Charge missed start time must be less than end time');
                    }
                }
            }
            if ($attribute == 'charge_missed_end_time') {
                if ($chargeMissedEndTime) {
                    $chargeMissedEndTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $chargeMissedEndTime");
                    if ($chargeMissedEndTime->isBefore($startTime)) {
                        $fail('Charge missed end time must be greater than start time');
                    }
                }
            }
        }

    }
}
