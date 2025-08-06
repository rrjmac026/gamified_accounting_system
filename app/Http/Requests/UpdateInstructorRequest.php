<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateInstructorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        $instructor = $this->route('instructor');
        
        // Admins can update any instructor
        if ($user->role === 'admin') {
            return true;
        }
        
        // Instructors can update their own profile (limited fields)
        if ($user->role === 'instructor' && $user->instructor->id === $instructor) {
            // Check if they're trying to update restricted fields
            $restrictedFields = ['employee_id', 'is_active'];
            foreach ($restrictedFields as $field) {
                if ($this->has($field)) {
                    return false;
                }
            }
            return true;
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $instructor = $this->route('instructor');
        $instructorModel = \App\Models\Instructor::findOrFail($instructor);
        
        return [
            // User data
            'id_number' => ['required', 'string', 'max:20', Rule::unique('users', 'id_number')->ignore($instructorModel->user_id)],
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-\']+$/',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($instructorModel->user_id)],
            'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'is_active' => 'boolean',
            
            // Instructor specific data
            'employee_id' => ['required', 'string', 'max:20', Rule::unique('instructors', 'employee_id')->ignore($instructor)],
            'department' => 'required|string|max:100',
            'specialization' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom attribute names for validator errors.
     */
    public function attributes(): array
    {
        return [
            'id_number' => 'ID number',
            'full_name' => 'full name',
            'employee_id' => 'employee ID',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'full_name.regex' => 'The full name may only contain letters, spaces, dots, hyphens, and apostrophes.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, and one number.',
            'employee_id.unique' => 'This employee ID is already registered.',
            'department.required' => 'Please specify the department.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Clean up the data before validation
        if ($this->has('full_name')) {
            $this->merge([
                'full_name' => trim($this->full_name)
            ]);
        }
        
        if ($this->has('specialization') && is_null($this->specialization)) {
            $this->merge([
                'specialization' => null
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
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}

