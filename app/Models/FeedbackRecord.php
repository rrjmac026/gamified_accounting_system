<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackRecord extends Model
{
    

    protected $fillable = [
        'student_id',
        'task_id',
        'feedback_type',
        'feedback_text',
        'recommendations',
        'generated_at',
        'is_read',
        'rating',
        'is_anonymous',
    ];

    protected $casts = [
        'recommendations' => 'array',
        'generated_at' => 'datetime',
        'is_read' => 'boolean'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
