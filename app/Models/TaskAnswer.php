<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_submission_id',
        'task_question_id',
        'answer_text',
        'is_correct',
        'points_earned'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'decimal:2'
    ];

    // Relationships
    public function submission()
    {
        return $this->belongsTo(TaskSubmission::class, 'task_submission_id');
    }

    public function question()
    {
        return $this->belongsTo(TaskQuestion::class, 'task_question_id');
    }
}
