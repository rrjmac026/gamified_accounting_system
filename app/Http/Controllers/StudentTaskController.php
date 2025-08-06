<?php

namespace App\Http\Controllers;

use App\Models\StudentTask;
use App\Models\Student;
use App\Models\Task;
use Illuminate\Http\Request;

class StudentTaskController extends Controller
{
    public function index()
    {
        $studentTasks = StudentTask::with(['student.user', 'task.subject'])->paginate(10);
        return view('student-tasks.index', compact('studentTasks'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $tasks = Task::with('subject')->get();
        return view('student-tasks.create', compact('students', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:assigned,in_progress,submitted,graded,overdue',
            'score' => 'nullable|numeric|min:0',
            'xp_earned' => 'nullable|integer|min:0',
            'submitted_at' => 'nullable|date',
            'graded_at' => 'nullable|date'
        ]);

        StudentTask::create($validated);
        return redirect()->route('student-tasks.index')
            ->with('success', 'Task assigned to student successfully');
    }

    public function show(StudentTask $studentTask)
    {
        $studentTask->load(['student.user', 'task.subject']);
        return view('student-tasks.show', compact('studentTask'));
    }

    public function edit(StudentTask $studentTask)
    {
        $students = Student::with('user')->get();
        $tasks = Task::with('subject')->get();
        return view('student-tasks.edit', compact('studentTask', 'students', 'tasks'));
    }

    public function update(Request $request, StudentTask $studentTask)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:assigned,in_progress,submitted,graded,overdue',
            'score' => 'nullable|numeric|min:0',
            'xp_earned' => 'nullable|integer|min:0',
            'submitted_at' => 'nullable|date',
            'graded_at' => 'nullable|date'
        ]);

        $studentTask->update($validated);
        return redirect()->route('student-tasks.show', $studentTask)
            ->with('success', 'Student task updated successfully');
    }

    public function destroy(StudentTask $studentTask)
    {
        $studentTask->delete();
        return redirect()->route('student-tasks.index')
            ->with('success', 'Student task deleted successfully');
    }

    public function grade(StudentTask $studentTask)
    {
        return view('student-tasks.grade', compact('studentTask'));
    }

    public function submitGrade(Request $request, StudentTask $studentTask)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'xp_earned' => 'required|integer|min:0'
        ]);

        $studentTask->update([
            'score' => $validated['score'],
            'xp_earned' => $validated['xp_earned'],
            'status' => 'graded',
            'graded_at' => now()
        ]);

        return redirect()->route('student-tasks.show', $studentTask)
            ->with('success', 'Task graded successfully');
    }
}
