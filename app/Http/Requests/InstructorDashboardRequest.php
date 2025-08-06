<?php

namespace App\Http\Requests\Instructor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class InstructorDashboardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        $instructorId = $this->route('instructor');
        
        // Admins can view any instructor dashboard
        if ($user->role === 'admin') {
            return true;
        }
        
        // Instructors can only view their own dashboard
        if ($user->role === 'instructor') {
            // If no instructor ID specified, they're viewing their own
            if (!$instructorId) {
                return true;
            }
            
            // Check if the requested instructor ID matches their own
            return $user->instructor && $user->instructor->id == $instructorId;
        }
        
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'period' => 'nullable|in:week,month,semester,year',
            'subject_id' => 'nullable|integer|exists:subjects,id',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'period.in' => 'Invalid time period selected.',
            'subject_id.exists' => 'Selected subject does not exist.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set default period if not specified
        $this->merge([
            'period' => $this->period ?? 'month',
        ]);
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
                    'message' => 'Dashboard request validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}
