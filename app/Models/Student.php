<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{

    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'student_number',
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
    

    public function tasks()
    {
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

    public function taskSubmissions()
    {
        return $this->hasMany(\App\Models\TaskSubmission::class);
    }

    public function submittedTasks()
    {
        return $this->belongsToMany(\App\Models\Task::class, 'task_submissions')
                    ->withPivot(['status', 'score', 'xp_earned', 'submitted_at', 'graded_at']);
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

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_student');
    }
}
