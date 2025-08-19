<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'name',
        'course_id',      // optional, if sections belong to a course
        'teacher_id',     // optional
        'capacity',
        'notes'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}