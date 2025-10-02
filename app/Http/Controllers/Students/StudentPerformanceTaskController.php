<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PerformanceTask;
use App\Models\PerformanceTaskSubmission;

class StudentPerformanceTaskController extends Controller
{
    /**
     * Show a performance task for the student to answer
     */
     public function index()
    {
        $tasks = PerformanceTask::all();
        return view('students.performance-tasks.index', compact('tasks'));
    }

    public function show($id)
    {
        $task = PerformanceTask::findOrFail($id);
        
        // Count how many attempts this student has made
        $attemptsUsed = PerformanceTaskSubmission::where('task_id', $id)
            ->where('student_id', auth()->id())
            ->count();

        return view('students.performance-tasks.show', compact('task', 'attemptsUsed'));
    }

    public function submit(Request $request, $id)
    {
        $task = PerformanceTask::findOrFail($id);
        
        // Check if student has attempts left
        $attemptsUsed = PerformanceTaskSubmission::where('task_id', $id)
            ->where('student_id', auth()->id())
            ->count();

        if ($attemptsUsed >= $task->max_attempts) {
            return back()->with('error', 'You have reached the maximum number of attempts.');
        }

        $request->validate([
            'submission_data' => 'required|json',
        ]);

        PerformanceTaskSubmission::create([
            'task_id' => $id,
            'student_id' => auth()->id(),
            'submission_data' => json_decode($request->submission_data, true),
            'status' => 'pending', // or 'submitted'
        ]);

        return redirect()->route('students.performance-tasks.index')
            ->with('success', 'Your answer has been submitted successfully!');
    }
}
