<?php

namespace App\Http\Requests;

use App\Models\MonthlyInvoicePackage;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMonthlyInvoicePackageRequest extends FormRequest
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
        $rules = MonthlyInvoicePackage::$rules;
        
        return $rules;
    }
}
