<?php

namespace App\Http\Requests;

use App\Models\Proctor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProctorRequest extends FormRequest
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
        $clientID = $this->route('proctor');
        return [
            'first_name' => 'required|string|min:2|max:255',
            'last_name' => 'nullable|string|min:2|max:255',
            'email' => ['required','email',Rule::unique('proctors')->ignore($clientID)],
            'password' => 'nullable|string|min:8|max:255',
        ];
    }
}
