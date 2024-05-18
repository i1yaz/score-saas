<?php

namespace App\Http\Requests\Landlord\Settings;

use Illuminate\Foundation\Http\FormRequest;

class CompanyDetailRequest extends FormRequest
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
            'company_name' => ['required'],
            'company_address_line_1' => ['required'],
            'company_city' => ['required'],
            'company_state' => ['required'],
            'company_zipcode' => ['required'],
            'company_country' => ['required'],
            'company_telephone' => ['required'],

        ];
    }

}
