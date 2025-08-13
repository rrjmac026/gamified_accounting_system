<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'department',
        'specialization'
    ];

    protected $with = ['user'];

    protected $appends = ['name', 'email', 'stats'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function students()
    {
        return $this->hasManyThrough(
            Student::class,
            Subject::class,
            'instructor_id', // Foreign key on subjects table
            'id', // Foreign key on students table
            'id', // Local key on instructors table
            'id'  // Local key on subjects table
        )->distinct();
    }

    // Add accessor for full name
    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : 'N/A';
    }

    // Add accessor for email
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : 'N/A';
    }

    public function getStatsAttribute()
    {
        return [
            'total_subjects' => $this->subjects()->count(),
            'total_tasks' => $this->tasks()->count(),
            'active_tasks' => $this->tasks()->where('is_active', true)->count(),
            'total_students' => $this->students()->count(),
        ];
    }
}
