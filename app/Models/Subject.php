<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
   

    protected $fillable = [
        'subject_code',
        'subject_name',
        'description',
        'instructor_id',
        'semester',
        'academic_year',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_subjects')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function performanceLogs()
    {
        return $this->hasMany(PerformanceLog::class);
    }

}
