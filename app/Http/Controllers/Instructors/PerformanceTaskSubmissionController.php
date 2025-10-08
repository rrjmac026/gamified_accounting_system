<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\PerformanceTask;
use App\Models\PerformanceTaskSubmission;
use App\Models\User;
use Illuminate\Http\Request;

class PerformanceTaskSubmissionController extends Controller
{
    // Show all students submissions for a specific performance task
    public function index(PerformanceTask $task)
    {
        // Load submissions grouped by student
        $submissions = PerformanceTaskSubmission::with('student')
            ->where('task_id', $task->id)
            ->orderBy('student_id')
            ->orderBy('step')
            ->get()
            ->groupBy('student_id'); // group submissions by student

        return view('instructors.performance-tasks.submissions.index', compact('task', 'submissions'));
    }

    // View details of a single student's submission
    public function show(PerformanceTask $task, User $student)
    {
        $submissions = PerformanceTaskSubmission::where('task_id', $task->id)
            ->where('student_id', $student->id)
            ->orderBy('step')
            ->get();

        return view('instructors.performance-tasks.submissions.show', compact('task', 'student', 'submissions'));
    }
}
