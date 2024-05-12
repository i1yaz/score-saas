<?php

namespace App\Http\Requests\Landlord\Packages;

use Illuminate\Foundation\Http\FormRequest;

class CreatePackage extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required',
            'price_monthly' => 'required|numeric',
            'price_yearly' => 'required|numeric',
            'max_students' => 'sometimes|nullable|integer',
            'max_student_packages' => 'sometimes|nullable|integer',
            'max_monthly_packages' => 'sometimes|nullable|integer',
            'max_teacher' => 'sometimes|nullable|integer',
        ];
    }
}
