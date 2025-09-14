<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * List all tasks assigned to the logged-in student.
     */
    public function index()
    {
        $student = auth()->user()->student;

        $tasks = $student->tasks()
            ->with([
                'subject',
                'submissions' => function ($query) use ($student) {
                    $query->where('student_id', $student->id)
                        ->latest('submitted_at');
                }
            ])
            ->get();


        return view('students.tasks.index', compact('tasks'));
    }

    /**
     * Show details of a single assigned task.
     */
    public function show(Task $task)
    {
        $student = Auth::user()->student;

        // Get the student-task pivot (status, score, etc.)
        $studentTask = $student->tasks()
            ->where('tasks.id', $task->id)
            ->firstOrFail();

        // Load relations for displaying task details
        $task->load(['subject', 'instructor.user', 'questions']);

        // Get the student’s latest submission
        $submission = $task->submissions()
            ->where('student_id', $student->id)
            ->latest('submitted_at')
            ->first();

        return view('students.tasks.show', compact('task', 'studentTask', 'submission'));
    }


    /**
     * Store student submission (file upload or answers).
     */
    public function submit(Request $request, Task $task)
    {
        $student = Auth::user()->student;

        if (!$student->tasks()->where('tasks.id', $task->id)->exists()) {
            abort(403, 'This task is not assigned to you.');
        }

        $validated = $request->validate([
            'file' => 'nullable|file|max:2048',
            'answers' => 'nullable|array',
        ]);

        $filePath = $request->hasFile('file')
            ? $request->file('file')->store('submissions', 'public')
            : null;

        // === Late handling ===
        $isLate = now()->gt($task->due_date);

        if ($isLate && !$task->allow_late_submission) {
            return back()->withErrors(['error' => 'Late submission is not allowed for this task.']);
        }

        if ($isLate && is_null($task->late_penalty)) {
            return back()->withErrors(['error' => 'The deadline has passed. You cannot submit this task.']);
        }

        $penaltyApplied = $isLate && $task->late_penalty > 0;

        // Get next attempt number
        $lastAttempt = TaskSubmission::where('task_id', $task->id)
            ->where('student_id', $student->id)
            ->max('attempt_number');
        $attemptNumber = $lastAttempt ? $lastAttempt + 1 : 1;

        // Save submission
        $submission = TaskSubmission::updateOrCreate(
            ['task_id' => $task->id, 'student_id' => $student->id],
            [
                'submission_data' => $validated['answers'] ?? [],
                'file_path' => $filePath,
                'status' => $isLate ? 'late' : 'submitted',
                'submitted_at' => now(),
                'attempt_number' => $attemptNumber,
            ]
        );

        // Update pivot
        $pivotData = [
            'status' => $isLate ? 'late' : 'submitted',
            'submitted_at' => now(),
        ];
        if ($penaltyApplied) {
            $pivotData['penalty'] = $task->late_penalty;
        }
        $student->tasks()->updateExistingPivot($task->id, $pivotData);

        return redirect()->route('students.tasks.show', $task)
            ->with('success', $isLate
                ? '⚠️ Task submitted late. A penalty will apply.'
                : '✅ Task submitted successfully!');
    }

}
