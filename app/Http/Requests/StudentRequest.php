<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|exists:users,id',
            'course' => 'required|string|max:100',
            'year_level' => 'required|integer|min:1|max:5',
            'section' => 'required|string|max:50',
            'subjects' => 'array|exists:subjects,id',
            'total_xp' => 'sometimes|integer|min:0',
            'current_level' => 'sometimes|integer|min:1',
            'performance_rating' => 'sometimes|numeric|min:0|max:100'
        ];
    }
}
