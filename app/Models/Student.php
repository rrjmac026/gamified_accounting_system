<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    protected $fillable = [
        'user_id',
        'course_id',
        'year_level',
        'section',
        'total_xp',
        'current_level',
        'performance_rating'
    ];

    protected $casts = [
        'total_xp' => 'integer',
        'current_level' => 'integer',
        'performance_rating' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    public function assignedTasks()
    {
        // Quick access: gives you Task models with pivot info
        return $this->belongsToMany(Task::class, 'student_tasks')
                    ->withPivot('status', 'score', 'xp_earned', 'submitted_at', 'graded_at', 'retry_count')
                    ->withTimestamps();
    }

    public function studentTasks()
    {
        // Full access: gives you StudentTask models
        return $this->hasMany(StudentTask::class);
    }

    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function performanceLogs()
    {
        return $this->hasMany(PerformanceLog::class);
    }

    public function quizScores()
    {
        return $this->hasMany(QuizScore::class);
    }

    public function xpTransactions()
    {
        return $this->hasMany(XpTransaction::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'student_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects')
                    ->withPivot('enrollment_date', 'status')
                    ->withTimestamps();
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
