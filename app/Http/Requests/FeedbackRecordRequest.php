<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'task_id' => 'required|exists:tasks,id',
            'feedback_type' => 'required|in:general,improvement,question',
            'feedback_text' => 'required|string|min:10',
            'recommendations' => 'required|string',
            'generated_at' => 'required|date',
            'is_read' => 'required|boolean',
            'rating' => 'required|integer|min:1|max:5'
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => 'Please provide a rating for this task.',
            'rating.integer' => 'Rating must be a valid number.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot be more than 5 stars.',
            'feedback_text.min' => 'Feedback must be at least 10 characters long.',
            'recommendations.required' => 'Please provide at least one recommendation.',
        ];
    }
}