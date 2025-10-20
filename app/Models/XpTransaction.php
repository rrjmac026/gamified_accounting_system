<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpTransaction extends Model
{
    

    protected $fillable = [
        'student_id',
        'amount',
        'type',
        'source', 
        'source_id',
        'description',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'integer',
        'processed_at' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function performanceTask()
    {
        return $this->belongsTo(PerformanceTask::class, 'performance_task_id');
    }
}
