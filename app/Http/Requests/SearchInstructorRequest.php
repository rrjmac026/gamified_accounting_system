<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;

class SearchInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any authenticated user can search instructors
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'query' => 'required|string|min:2|max:100',
            'department' => 'nullable|string|max:100',
            'specialization' => 'nullable|string|max:100',
            'has_subjects' => 'nullable|in:yes,no',
            'limit' => 'nullable|integer|min:1|max:50',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'query.required' => 'Please enter a search term.',
            'query.min' => 'Search term must be at least 2 characters long.',
            'query.max' => 'Search term cannot exceed 100 characters.',
            'limit.min' => 'Limit must be at least 1.',
            'limit.max' => 'Limit cannot exceed 50 results.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('query')) {
            $this->merge([
                'query' => trim($this->query)
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        if ($this->expectsJson()) {
            throw new \Illuminate\Http\Exceptions\HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Search validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
