<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_code',
        'name',
        'course_id',   // optional if sections belong to a course
        'capacity',
        'notes',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'section_student');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    // Optional helper to get instructors teaching in this section
    // public function instructors()
    // {
    //     return $this->hasManyThrough(Instructor::class, Subject::class);
    // }
    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'instructor_section', 'section_id', 'instructor_id');
    }

}
