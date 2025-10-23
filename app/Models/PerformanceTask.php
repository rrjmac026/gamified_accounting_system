<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceTask extends Model
{
    protected $fillable = [
            'title',
            'description',
            'max_attempts',
            'xp_reward',
            'subject_id',
            'section_id',
            'instructor_id',
            'due_date',
            'late_until',
            'max_score',
            'deduction_per_error',
        ];

    protected $casts = [
        'template_data' => 'array',
        'due_date' => 'datetime',
        'late_until' => 'datetime',
    ];

    // 🔹 Relation to submissions
    public function submissions()
    {
        return $this->hasMany(PerformanceTaskSubmission::class, 'task_id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'performance_task_student')
            ->withPivot('status', 'submission_data', 'score', 'feedback', 'submitted_at', 'graded_at', 'attempts')
            ->withTimestamps();
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function steps()
    {
        return $this->hasMany(PerformanceTaskStep::class, 'performance_task_id');
    }

    public function answerSheets()
    {
        return $this->hasMany(PerformanceTaskAnswerSheet::class);
    }

    public function xpTransactions()
    {
        return $this->hasMany(XpTransaction::class, 'performance_task_id');
    }
}
