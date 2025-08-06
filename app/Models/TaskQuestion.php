<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'question_text',
        'question_type', // multiple_choice, true_false, essay, calculation
        'correct_answer',
        'points',
        'order_index',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
        'order_index' => 'integer'
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function answers()
    {
        return $this->hasMany(TaskAnswer::class);
    }
}
