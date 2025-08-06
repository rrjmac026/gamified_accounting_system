<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaderboardRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'rank_position' => 'required|integer|min:1',
            'total_xp' => 'required|integer|min:0',
            'total_score' => 'required|numeric|min:0',
            'tasks_completed' => 'required|integer|min:0',
            'period_type' => 'required|in:weekly,monthly,semester,overall',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start'
        ];
    }
}
