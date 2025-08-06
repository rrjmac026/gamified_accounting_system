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
            'icon_path' => 'required|string',
            'criteria' => 'required|array',
            'xp_threshold' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ];
    }
}
