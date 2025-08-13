<?php

namespace App\Http\Controllers;

use App\Models\TaskSubmission;
use App\Models\Task;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\XpEngine;

class TaskSubmissionController extends Controller
{
    public function index()
    {
        $taskSubmissions = TaskSubmission::with(['task', 'student'])->get();
        return view('task-submissions.index', compact('taskSubmissions'));
    }

    public function create()
    {
        $tasks = Task::all();
        $students = Student::all();
        return view('task-submissions.create', compact('tasks', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'student_id' => 'required|exists:students,id',
            'submission_data' => 'required|array',
            'file_path' => 'nullable|string',
            'status' => 'required|in:pending,graded,late,incomplete',
            'submitted_at' => 'required|date',
            'attempt_number' => 'required|integer|min:1'
        ]);

        $taskSubmission = TaskSubmission::create($validated);
        return redirect()->route('task-submissions.index')
            ->with('success', 'Task submission created successfully');
    }

    public function show(TaskSubmission $taskSubmission)
    {
        $taskSubmission->load(['task', 'student', 'answers', 'errors']);
        return view('task-submissions.show', compact('taskSubmission'));
    }

    public function edit(TaskSubmission $taskSubmission)
    {
        $tasks = Task::all();
        $students = Student::all();
        return view('task-submissions.edit', compact('taskSubmission', 'tasks', 'students'));
    }

    public function update(Request $request, TaskSubmission $taskSubmission)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'student_id' => 'required|exists:students,id',
            'submission_data' => 'required|array',
            'file_path' => 'nullable|string',
            'score' => 'nullable|numeric|min:0',
            'xp_earned' => 'nullable|integer|min:0',
            'status' => 'required|in:pending,graded,late,incomplete',
            'feedback' => 'nullable|string',
            'graded_at' => 'nullable|date'
        ]);

        $taskSubmission->update($validated);
        return redirect()->route('task-submissions.index')
            ->with('success', 'Task submission updated successfully');
    }

    public function destroy(TaskSubmission $taskSubmission)
    {
        $taskSubmission->delete();
        return redirect()->route('task-submissions.index')
            ->with('success', 'Task submission deleted successfully');
    }

    public function grade(Request $request, TaskSubmission $taskSubmission, XpEngine $xpEngine)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'xp_earned' => 'required|integer|min:0',
            'feedback' => 'required|string'
        ]);

        // Update submission
        $taskSubmission->update([
            'score' => $validated['score'],
            'xp_earned' => $validated['xp_earned'],
            'feedback' => $validated['feedback'],
            'status' => 'graded',
            'graded_at' => now()
        ]);

        // Award XP automatically
        $xpEngine->award(
            studentId: $taskSubmission->student_id,
            amount: $validated['xp_earned'],
            type: 'earned',
            source: 'task_completion',
            sourceId: $taskSubmission->task_id,
            description: "Earned {$validated['xp_earned']} XP for completing '{$taskSubmission->task->title}'"
        );

        return redirect()->route('task-submissions.show', $taskSubmission)
            ->with('success', 'Task submission graded and XP awarded successfully');
    }
    
}
