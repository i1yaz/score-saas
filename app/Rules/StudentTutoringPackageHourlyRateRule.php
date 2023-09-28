<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StudentTutoringPackageHourlyRateRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $tutors = request()->tutor_ids;
        if (count($tutors) > 1) {
            if (request()->tutor_hourly_rate == null) {
                $fail('Tutor Hourly rate is required when more than one tutor is selected');
            }
            if (!is_numeric(request()->tutor_hourly_rate)) {
                $fail('Tutor Hourly rate must be numeric');
            }
            if (request()->tutor_hourly_rate < 1) {
                $fail('Tutor Hourly rate must be greater than 0');
            }
        }elseif ($tutors == 1 && !empty(request()->tutor_hourly_rate)) {
            if (!is_numeric(request()->tutor_hourly_rate)) {
                $fail('Tutor Hourly rate must be numeric');
            }
            if (request()->tutor_hourly_rate < 1) {
                $fail('Tutor Hourly rate must be greater than 0');
            }
        }

    }
}
