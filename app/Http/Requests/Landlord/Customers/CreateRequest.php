<?php

namespace App\Http\Requests\Landlord\Customers;

use App\Models\Landlord\Setting;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest {

    public function authorize() {
        return true;
    }

    /**
     * custom error messages for specific valdation checks
     * @optional
     * @return array
     */
    public function messages() {
        return [
            'full_name.required' => __('lang.first_name') . ' - ' . __('lang.is_required'),
            'email_address.required' => __('lang.email') . ' - ' . __('lang.is_required'),
            'email_address.unique' => __('lang.email') . ' - ' . __('lang.already_exists'),
            'account_name.required' => __('lang.account_name') . ' - ' . __('lang.is_required'),
            'account_name.unique' => __('lang.account_url') . ' - ' . __('lang.already_exists'),
            'plan.required' => __('lang.plan') . ' - ' . __('lang.is_required'),
            'plan.exists' => __('lang.package_not_found'),
        ];
    }

    /**
     * Validate the request
     * @return array
     */
    public function rules() {

        $rules = [
            'full_name' => [
                'required',
            ],
            'email_address' => [
                'required',
                Rule::unique('tenants', 'email')->ignore(request()->route('customer'), 'id'),
            ],
            'account_name' => [
                'required',
                Rule::unique('tenants', 'subdomain')->ignore(request()->route('customer'), 'id'),
                function ($attribute, $value, $fail) {
                    //validate domain name characters (a-z A-Z 0-9 . -)
                    if (!preg_match('/^[a-zA-Z0-9]+[a-zA-Z0-9-._]*[a-zA-Z0-9]+$/', $value)) {
                        return $fail(__('lang.account_url_is_invalid'));
                    }
                },
                //validate reserved words
                function ($attribute, $value, $fail) {
                    $settings = Setting::on('landlord')->Where('id', 'default')->first();
                    $reserved_words = explode(',', $settings->reserved_words);
                    $reserved_words = array_map('trim', $reserved_words);
                    if (in_array($value, $reserved_words)) {
                        return $fail(__('lang.reserved_words_error'));
                    }
                },
            ],
            'plan' => [
                'required',
                Rule::exists('packages', 'id'),
            ],
        ];

        /**-------------------------------------------------------
         * [create] only rules
         * ------------------------------------------------------*/
        if ($this->getMethod() == 'POST') {

        }

        /**-------------------------------------------------------
         * [update] only rules
         * ------------------------------------------------------*/
        if ($this->getMethod() == 'PUT') {

        }

        //validate
        return $rules;
    }


}
