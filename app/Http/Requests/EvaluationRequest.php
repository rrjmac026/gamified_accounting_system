<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EvaluationRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && Auth::user()->role === 'student';
    }

    public function rules()
    {
        return [
            'instructor_id' => 'required|exists:instructors,id',
            'course_id' => 'required|exists:courses,id',
            'responses' => 'required|array',
            'responses.*' => 'required|integer|min:1|max:5',
            'comments' => 'required|string|min:10|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'instructor_id.required' => 'Please select an instructor.',
            'instructor_id.exists' => 'The selected instructor is invalid.',
            'course_id.required' => 'Please select a course.',
            'course_id.exists' => 'The selected course is invalid.',
            'responses.required' => 'Please provide ratings for all criteria.',
            'responses.array' => 'Invalid response format.',
            'responses.*.required' => 'Please rate all criteria.',
            'responses.*.integer' => 'Ratings must be numbers.',
            'responses.*.min' => 'Ratings must be between 1 and 5.',
            'responses.*.max' => 'Ratings must be between 1 and 5.',
            'comments.required' => 'Please provide your comments.',
            'comments.min' => 'Comments must be at least 10 characters.',
            'comments.max' => 'Comments cannot exceed 1000 characters.',
        ];
    }
}
