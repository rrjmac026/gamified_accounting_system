<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceLog extends Model
{
    

    protected $fillable = [
        'student_id',
        'subject_id',
        'task_id',
        'performance_metric',
        'value',
        'recorded_at'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'recorded_at' => 'datetime'
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

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
