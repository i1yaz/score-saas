<?php

namespace App\Http\Requests\Landlord\Settings;

use Illuminate\Foundation\Http\FormRequest;

class GeneralRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'system_date_format' => [ 'required' ],
            'system_renewal_grace_period' => ['required','integer','min:1']
        ];
    }

    public function messages(): array
    {
        return [
            'system_date_format.required' => __('lang.date_format') . ' - ' . __('lang.is_required'),
            'system_renewal_grace_period.required' => __('lang.renewal_grace_period') . ' - ' . __('lang.is_required'),
            'system_renewal_grace_period.integer' => __('lang.renewal_grace_period') . ' - ' . __('lang.must_be_integer'),
            'system_renewal_grace_period.min' => __('lang.renewal_grace_period') . ' - ' . __('lang.must_be_greater_than_zero'),
        ];
    }


}
