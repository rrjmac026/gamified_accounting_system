<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'student_id',
        'submission_data',
        'file_path',
        'score',
        'xp_earned',
        'status', // pending, graded, late, incomplete
        'submitted_at',
        'graded_at',
        'feedback',
        'attempt_number'
    ];

    protected $casts = [
        'submission_data' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'score' => 'decimal:2',
        'xp_earned' => 'integer',
        'attempt_number' => 'integer'
    ];

    // Relationships
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function answers()
    {
        return $this->hasMany(TaskAnswer::class);
    }

    public function errors()
    {
        return $this->hasMany(ErrorRecord::class);
    }
}
