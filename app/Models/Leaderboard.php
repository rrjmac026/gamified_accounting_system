<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'rank_position',
        'total_xp',
        'total_score',
        'tasks_completed',
        'period_type', // weekly, monthly, semester, overall
        'period_start',
        'period_end'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'total_xp' => 'integer',
        'total_score' => 'decimal:2',
        'tasks_completed' => 'integer',
        'rank_position' => 'integer'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
