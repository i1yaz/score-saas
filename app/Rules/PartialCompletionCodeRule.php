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
        $completionCode = request()->session_completion_code;
        $startTime = date('H:i', strtotime(request()->start_time));
        $endTime = date('H:i', strtotime(request()->end_time));
        $startTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $startTime");
        $endTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $endTime");
        $attendedStartTime = date('H:i', strtotime(request()->attended_start_time));
        $attendedEndTime = date('H:i', strtotime(request()->attended_end_time));
        if ((int) $completionCode === 2) {
            if (empty($attendedStartTime)) {
                $fail('Attended start time is required');
            }
            if (empty($attendedEndTime)) {
                $fail('Attended end time is required');
            }
            if (! empty($attendedStartTime) && ! empty($attendedEndTime)) {
                $attendedStartTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $attendedStartTime");
                $attendedEndTime = Carbon::createFromFormat('m/d/Y H:i', "$scheduledDate $attendedEndTime");
                if ($attribute == 'attended_start_time') {
                    if (!$attendedStartTime->isBetween($startTime, $endTime) ) {
                        $fail('Attended start time must be between session start time and session end time');
                    }
                }
                if ($attribute == 'attended_end_time') {
                    if (!$attendedEndTime->isBetween($startTime, $endTime) ) {
                        $fail('Attended end time must be between session start time and session end time');
                    }
                }
            }
        }

    }
}
