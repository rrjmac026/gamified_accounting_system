<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'subjects'])->paginate(10);
        return view('admin.student.index', compact('student'));
    }

    // public function create()
    // {
    //     $subjects = Subject::all();
    //     return view('instructors.students.create', compact('subjects'));
    // }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'user_id' => 'required|exists:users,id',
    //         'course' => 'required|string|max:100',
    //         'year_level' => 'required|integer|min:1|max:5',
    //         'section' => 'required|string|max:50',
    //         'subjects' => 'array|exists:subjects,id'
    //     ]);

    //     $student = Student::create($validated);

    //     if(isset($validated['subjects'])) {
    //         $student->subjects()->attach($validated['subjects']);
    //     }

    //     return redirect()->route('students.index')
    //         ->with('success', 'Student created successfully');
    // }

    public function show(Student $student)
    {
        $student->load(['user', 'subjects', 'badges', 'assignedTasks']);
        return view('instructors.student.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $subjects = Subject::all();
        return view('admin.student.edit', compact('student', 'subjects'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'course' => 'required|string|max:100',
            'year_level' => 'required|integer|min:1|max:5',
            'section' => 'required|string|max:50',
            'subjects' => 'array|exists:subjects,id'
        ]);

        $student->update($validated);

        if(isset($validated['subjects'])) {
            $student->subjects()->sync($validated['subjects']);
        }

        return redirect()->route('admin.student.show', $student)
            ->with('success', 'Student updated successfully');
    }

    // public function destroy(Student $student)
    // {
    //     $student->delete();
    //     return redirect()->route('students.index')
    //         ->with('success', 'Student deleted successfully');
    // }

    // public function viewProgress(Student $student)
    // {
    //     $student->load(['performanceLogs', 'quizScores', 'badges']);
    //     return view('instructors.students.progress', compact('student'));
    // }

    public function viewAssignments(Student $student)
    {
        $assignments = $student->assignedTasks()
            ->with(['subject'])
            ->orderBy('due_date', 'desc')
            ->paginate(10);
            
        return view('instructors.students.assignments', compact('student', 'assignments'));
    }
}
