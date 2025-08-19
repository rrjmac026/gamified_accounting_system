<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BadgeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'xp_threshold' => 'required|integer|min:0',
            'criteria' => 'required|in:achievement,skill,participation,milestone',
        ];
    }
}

