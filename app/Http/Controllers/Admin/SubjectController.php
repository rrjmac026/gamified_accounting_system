<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller; 

use App\Models\Subject;
use App\Models\Instructor;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('instructor')->get();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $instructors = Instructor::all();
        return view('admin.subjects.create', compact('instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|unique:subjects,subject_code',
            'subject_name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_id' => 'required|exists:instructors,id',
            'semester' => 'required|string',
            'academic_year' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        Subject::create($validated);
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject created successfully');
    }

    public function show(Subject $subject)
    {
        $subject->load(['instructor', 'students', 'tasks']);
        return view('admin.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $instructors = Instructor::all();
        return view('admin.subjects.edit', compact('subject', 'instructors'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'subject_code' => 'required|string|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string|max:255',
            'description' => 'required|string',
            'instructor_id' => 'required|exists:instructors,id',
            'semester' => 'required|string',
            'academic_year' => 'required|string',
            'is_active' => 'required|boolean'
        ]);

        $subject->update($validated);
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')
            ->with('success', 'Subject deleted successfully');
    }
}
