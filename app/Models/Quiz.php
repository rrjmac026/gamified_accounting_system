<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'task_id', 
        'type', 
        'question_text', 
        'options', 
        'correct_answer', 
        'points',
        'quiz_file_path',
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function answers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}

