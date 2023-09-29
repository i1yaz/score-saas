<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'student_tutoring_package_id' => ['required', 'integer'],
            'scheduled_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'tutoring_location_id' => ['required', 'integer'],
            'pre_session_notes' => ['nullable'],
            'session_completion_code' => ['nullable', 'integer'],
            'how_was_session' => ['nullable', 'integer'],
            'student_parent_session_notes' => ['nullable'],
            'homework' => ['nullable'],
            'internal_notes' => ['nullable'],
            'flag_session' => ['nullable'],
            'home_work_completed' => ['nullable'],
            'practice_test_for_homework' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
