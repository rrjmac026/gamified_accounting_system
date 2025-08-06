<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can create users
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id_number' => 'required|string|max:20|unique:users,id_number',
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-\']+$/',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|in:student,instructor,admin',
            'admin_level' => 'nullable|required_if:role,admin|in:super_admin,admin,moderator',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:100',
            'is_active' => 'boolean',
            
            // Student specific fields
            'course' => 'nullable|required_if:role,student|string|max:100',
            'year_level' => 'nullable|required_if:role,student|integer|min:1|max:5',
            'section' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-]+$/',
            
            // Instructor specific fields
            'employee_id' => 'nullable|required_if:role,instructor|string|max:20|unique:instructors,employee_id',
            'department' => 'nullable|string|max:100',
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
            'year_level' => 'year level',
            'employee_id' => 'employee ID',
            'admin_level' => 'admin level',
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
            'section.regex' => 'The section may only contain letters, numbers, and hyphens.',
            'course.required_if' => 'The course field is required when role is student.',
            'year_level.required_if' => 'The year level field is required when role is student.',
            'employee_id.required_if' => 'The employee ID field is required when role is instructor.',
            'admin_level.required_if' => 'The admin level field is required when role is admin.',
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
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}

// ==========================================

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admins can update users, or users can update their own basic info
        $user = Auth::user();
        $targetUser = $this->route('user');
        
        if ($user->role === 'admin') {
            return true;
        }
        
        // Users can only update their own non-critical information
        if ($user->id === $targetUser) {
            // Check if they're trying to update restricted fields
            $restrictedFields = ['role', 'admin_level', 'permissions', 'is_active'];
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
        $user = $this->route('user');
        
        return [
            'id_number' => ['required', 'string', 'max:20', Rule::unique('users', 'id_number')->ignore($user)],
            'full_name' => 'required|string|max:255|regex:/^[a-zA-Z\s\.\-\']+$/',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password' => 'nullable|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'role' => 'required|in:student,instructor,admin',
            'admin_level' => 'nullable|required_if:role,admin|in:super_admin,admin,moderator',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:100',
            'is_active' => 'boolean',
            
            // Student specific fields
            'course' => 'nullable|required_if:role,student|string|max:100',
            'year_level' => 'nullable|required_if:role,student|integer|min:1|max:5',
            'section' => 'nullable|string|max:50|regex:/^[a-zA-Z0-9\-]+$/',
            
            // Instructor specific fields
            'employee_id' => [
                'nullable',
                'required_if:role,instructor',
                'string',
                'max:20',
                Rule::unique('instructors', 'employee_id')->ignore($user->instructor?->id ?? null)
            ],
            'department' => 'nullable|string|max:100',
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
            'year_level' => 'year level',
            'employee_id' => 'employee ID',
            'admin_level' => 'admin level',
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
            'section.regex' => 'The section may only contain letters, numbers, and hyphens.',
            'course.required_if' => 'The course field is required when role is student.',
            'year_level.required_if' => 'The year level field is required when role is student.',
            'employee_id.required_if' => 'The employee ID field is required when role is instructor.',
            'admin_level.required_if' => 'The admin level field is required when role is admin.',
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
        
        if ($this->has('permissions') && is_null($this->permissions)) {
            $this->merge([
                'permissions' => []
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

// ==========================================

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anyone can attempt to login
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string',
            'remember' => 'boolean',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Login validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}

// ==========================================

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssignRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        
        // Only super_admin and admin can assign roles
        if ($user->role !== 'admin') {
            return false;
        }
        
        // Super admin can assign any role
        if ($user->admin_level === 'super_admin') {
            return true;
        }
        
        // Regular admin cannot assign super_admin role
        if ($this->admin_level === 'super_admin') {
            return false;
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'role' => 'required|in:student,instructor,admin',
            'admin_level' => 'nullable|required_if:role,admin|in:super_admin,admin,moderator',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:100',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'admin_level.required_if' => 'The admin level is required when assigning admin role.',
            'role.in' => 'Invalid role selected.',
            'admin_level.in' => 'Invalid admin level selected.',
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
                    'message' => 'Role assignment validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}

// ==========================================

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BulkOperationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user();
        
        // Only admins can perform bulk operations
        if ($user->role !== 'admin') {
            return false;
        }
        
        // Super admin can perform any bulk operation
        if ($user->admin_level === 'super_admin') {
            return true;
        }
        
        // Regular admin has some restrictions
        if ($this->operation === 'assign_role' && $this->role === 'admin' && $this->admin_level === 'super_admin') {
            return false; // Cannot bulk assign super_admin role
        }
        
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'operation' => 'required|in:activate,deactivate,delete,assign_role',
            'user_ids' => 'required|array|min:1|max:100',
            'user_ids.*' => 'integer|exists:users,id',
            'role' => 'nullable|required_if:operation,assign_role|in:student,instructor,admin',
            'admin_level' => 'nullable|required_if:role,admin|in:super_admin,admin,moderator',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:100',
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
            'user_ids.required' => 'Please select at least one user.',
            'user_ids.min' => 'Please select at least one user.',
            'user_ids.max' => 'You can select a maximum of 100 users at once.',
            'user_ids.*.exists' => 'One or more selected users do not exist.',
            'role.required_if' => 'Role is required when operation is assign_role.',
            'admin_level.required_if' => 'Admin level is required when assigning admin role.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Remove current user from bulk operations to prevent self-modification
        if ($this->has('user_ids') && is_array($this->user_ids)) {
            $currentUserId = Auth::id();
            $filteredIds = array_filter($this->user_ids, fn($id) => $id != $currentUserId);
            
            $this->merge([
                'user_ids' => array_values($filteredIds) // Re-index array
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
                    'message' => 'Bulk operation validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}

// ==========================================

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Any authenticated user can search
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'query' => 'required|string|min:2|max:100',
            'role' => 'nullable|in:student,instructor,admin',
            'status' => 'nullable|in:active,inactive',
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

// ==========================================

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'role' => 'nullable|in:student,instructor,admin',
            'status' => 'nullable|in:active,inactive',
            'search' => 'nullable|string|max:100',
            'sort_by' => 'nullable|in:full_name,email,created_at,last_login_at,id_number',
            'sort_order' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:5|max:100',
            'admin_level' => 'nullable|in:super_admin,admin,moderator',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'per_page.min' => 'Results per page must be at least 5.',
            'per_page.max' => 'Results per page cannot exceed 100.',
            'sort_by.in' => 'Invalid sort field selected.',
            'sort_order.in' => 'Sort order must be either ascending or descending.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Set defaults
        $this->merge([
            'sort_by' => $this->sort_by ?? 'created_at',
            'sort_order' => $this->sort_order ?? 'desc',
            'per_page' => $this->per_page ?? 15,
        ]);

        // Clean search term
        if ($this->has('search')) {
            $this->merge([
                'search' => trim($this->search)
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
                    'message' => 'Filter validation failed',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }
}