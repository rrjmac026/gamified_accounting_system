<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BulkInstructorOperationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can perform bulk operations on instructors
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'operation' => 'required|in:activate,deactivate,delete,assign_department',
            'instructor_ids' => 'required|array|min:1|max:50',
            'instructor_ids.*' => 'integer|exists:instructors,id',
            'department' => 'nullable|required_if:operation,assign_department|string|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'operation.required' => 'Please select an operation to perform.',
            'operation.in' => 'Invalid operation selected.',
            'instructor_ids.required' => 'Please select at least one instructor.',
            'instructor_ids.min' => 'Please select at least one instructor.',
            'instructor_ids.max' => 'You can select a maximum of 50 instructors at once.',
            'instructor_ids.*.exists' => 'One or more selected instructors do not exist.',
            'department.required_if' => 'Department is required when operation is assign_department.',
        ];
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
                    'message' => 'Bulk operation validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
