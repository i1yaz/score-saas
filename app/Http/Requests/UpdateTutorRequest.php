<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTutorRequest extends FormRequest
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
        return [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('tutors', 'email')->ignore($this->email, 'email')],
            'picture' => ['sometimes', 'mimes:jpg,bmp,png,jpeg,JPG,BMP,PNG,JPEG', 'max:2048'],
            'resume' => ['sometimes', 'mimes:doc,docx,docm,pdf', 'max:2048'],
            'hourly_rate' => ['required', 'numeric', 'gt:0'],
        ];
    }
}
