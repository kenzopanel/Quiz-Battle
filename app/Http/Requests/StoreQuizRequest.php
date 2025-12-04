<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->is_admin ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'timeout_seconds' => 'required|integer|min:30|max:300',
            'per_question_time' => 'required|integer|min:5|max:60',
        ];
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'title.required' => 'The quiz title is required.',
            'title.max' => 'The quiz title must not exceed 255 characters.',
            'description.max' => 'The quiz description must not exceed 1000 characters.',
            'timeout_seconds.required' => 'The quiz timeout is required.',
            'timeout_seconds.min' => 'The quiz timeout must be at least 30 seconds.',
            'timeout_seconds.max' => 'The quiz timeout must not exceed 5 minutes.',
            'per_question_time.required' => 'The time per question is required.',
            'per_question_time.min' => 'Time per question must be at least 5 seconds.',
            'per_question_time.max' => 'Time per question must not exceed 60 seconds.',
        ];
    }
}
