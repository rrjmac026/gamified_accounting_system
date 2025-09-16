<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class InstructorSubjectController extends Controller
{
    // List all subjects assigned to this instructor
    public function index()
    {
        $instructor = auth()->user()->instructor;

        // Get subjects assigned to this instructor
        $subjects = $instructor->subjects()->with('tasks', 'sections')->get();

        return view('instructors.subjects.index', compact('subjects'));
    }

    // Show details of a single subject
    public function show($subjectId)
    {
        $instructor = auth()->user()->instructor;

        // Only allow access if this subject belongs to this instructor
        $subject = $instructor->subjects()->with('tasks', 'sections.students')->findOrFail($subjectId);

        return view('instructors.subjects.show', compact('subject'));
    }
}
