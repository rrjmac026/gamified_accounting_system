<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizScoreRequest extends FormRequest
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
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'time_taken' => 'required|integer|min:0',
            'attempt_number' => 'required|integer|min:1',
            'completed_at' => 'required|date'
        ];
    }
}
