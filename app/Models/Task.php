<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'subject_id',
        'instructor_id',
        'difficulty_level',
        'max_score',
        'xp_reward',
        'retry_limit',
        'late_penalty',
        'due_date',
        'instructions',
        'status',
        'is_active',
        'auto_grade'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_active' => 'boolean',
        'auto_grade' => 'boolean',
        'max_score' => 'integer',
        'xp_reward' => 'integer',
        'retry_limit' => 'integer',
        'late_penalty' => 'integer',
        'difficulty_level' => 'integer'
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_tasks')
                    ->withPivot('status', 'score', 'xp_earned', 'submitted_at', 'graded_at', 'retry_count') // maybe add retry count here
                    ->withTimestamps();
    }
    
    public function studentTasks()
    {
        return $this->hasMany(StudentTask::class);
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function questions()
    {
        return $this->hasMany(TaskQuestion::class);
    }

    public function performanceLogs()
    {
        return $this->hasMany(PerformanceLog::class);
    }

}
