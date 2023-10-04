<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SessionStartAndEndTimeRule implements ValidationRule
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
        if ($attribute == 'start_time') {
            if ($startTime->isAfter($endTime)) {
                $fail('Start time must be less than end time');
            }
        }
        if ($attribute == 'end_time') {
            if ($endTime->isBefore($startTime)) {
                $fail('End time must be greater than start time');
            }
        }

    }
}
