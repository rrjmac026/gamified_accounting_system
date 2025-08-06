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
            'feedback_type' => 'required|in:automated,manual,ai_generated',
            'feedback_text' => 'required|string',
            'recommendations' => 'required|array',
            'generated_at' => 'required|date',
            'is_read' => 'required|boolean'
        ];
    }
}
