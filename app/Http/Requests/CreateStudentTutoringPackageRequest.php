<?php

namespace App\Http\Requests;

use App\Models\StudentTutoringPackage;
use App\Rules\StudentTutoringPackageHourlyRateRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateStudentTutoringPackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return  [
            'student_id' => ['required'],
            'tutoring_package_type_id' => ['required'],
            'tutor_ids' => ['required', 'array', 'min:1'],
            'subject_ids' => ['required', 'array', 'min:1'],
            'tutoring_location_id' => ['required'],
            'internal_notes' => ['string'],
            'hours' => ['required', 'numeric', 'min:1'],
            'hourly_rate' => ['required', 'numeric', 'min:1'],
            'discount_type' => ['required'],
            'start_date' => ['required'],
            'tutor_hourly_rate' => [ new StudentTutoringPackageHourlyRateRule]
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return StudentTutoringPackage::$messages;
    }
}
