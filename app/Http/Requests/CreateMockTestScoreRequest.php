<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMockTestScoreRequest extends FormRequest
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
        $rules = [];
        $fields = [
            'english_score', 'math_score', 'reading_score', 'science_score',
            'verbal_reasoning_score', 'quantitative_reasoning_score',
            'reading_comprehension_score', 'mathematics_achievement_score',
            'quantitative_reasoning_1_score', 'quantitative_reasoning_2_score',
            'verbal_reasoning_score'
        ];
        //if any above field appear as input it should be numeric
        foreach ($fields as $field) {
            $rules[$field] = 'numeric';
        }

        return array_merge([
            'score' => 'required|numeric',
        ],$rules);
    }
}
