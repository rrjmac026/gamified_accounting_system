<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course',
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
        return $this->belongsToMany(Task::class, 'student_tasks')
                    ->withPivot('status', 'score', 'xp_earned', 'submitted_at', 'graded_at')
                    ->withTimestamps();
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
                    ->withTimestamps();
    }
}
