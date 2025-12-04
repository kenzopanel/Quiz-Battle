<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
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
            'question_text' => 'required|string|max:1000',
            'options' => 'required|array|min:2|max:6',
            'options.*' => 'required|string|max:255',
            'correct_option' => 'required|integer|min:0',
        ];
    }

    /**
     * Configure the validator.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->correct_option >= count(array_filter($this->options))) {
                $validator->errors()->add('correct_option', 'The correct option selection is invalid.');
            }

            $nonEmptyOptions = array_filter($this->options, function ($option) {
                return !empty(trim($option));
            });

            if (count($nonEmptyOptions) < 2) {
                $validator->errors()->add('options', 'At least 2 options are required.');
            }
        });
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'question_text.required' => 'The question text is required.',
            'question_text.max' => 'The question text must not exceed 1000 characters.',
            'options.required' => 'Please provide answer options.',
            'options.min' => 'At least 2 answer options are required.',
            'options.max' => 'Maximum 6 answer options are allowed.',
            'options.*.required' => 'All option fields must be filled.',
            'options.*.max' => 'Each option must not exceed 255 characters.',
            'correct_option.required' => 'Please select the correct answer.',
        ];
    }
}
