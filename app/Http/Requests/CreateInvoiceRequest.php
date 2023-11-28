<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
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
        return Invoice::$rules;
    }

    public function messages()
    {
        return [
            'client_id.required' => 'Client is required',
            'due_date.required' => 'Due date is required',
            'due_date.date' => 'Due date must be a valid date',
            'due_date.after_or_equal' => 'Due date must be after or equal to today',
            'item_id.required' => 'Item is required',
            'item_id.array' => 'Item must be an array',
            'item_id.min' => 'Item must have at least one item',
        ];
    }
}
