<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\XpEngine;

class TaskSubmissionController extends Controller
{
    /**
     * List all submissions for a specific task owned by the instructor.
     */
    public function index()
    {
        $instructor = auth()->user()->instructor;

        $taskSubmissions = TaskSubmission::whereHas('task', function ($query) use ($instructor) {
            $query->where('instructor_id', $instructor->id);
        })
        ->with(['task', 'student.user'])
        ->latest('submitted_at')
        ->paginate(10);

        return view('instructors.submissions.index', compact('taskSubmissions'));
    }


    /**
     * Show details of a student submission.
     */
    public function show(TaskSubmission $taskSubmission)
    {
        $instructor = Auth::user()->instructor;

        // Prevent viewing if not the task owner
        if ($taskSubmission->task->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized access to this submission.');
        }

        $taskSubmission->load(['task', 'student.user']);

        return view('instructors.submissions.show', compact('taskSubmission'));
    }

    /**
     * Grade a student’s submission and award XP.
     */
    public function grade(Request $request, TaskSubmission $taskSubmission, XpEngine $xpEngine)
    {
        $instructor = Auth::user()->instructor;

        // Prevent grading if not the task owner
        if ($taskSubmission->task->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized to grade this submission.');
        }

        // 1. Validate input first
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'xp_earned' => 'required|integer|min:0',
            'feedback' => 'required|string',
        ]);

        // 2. Apply late penalty if needed
        $finalScore = $validated['score'];
        if (!empty($taskSubmission->task->late_penalty) && $taskSubmission->status === 'late') {
            $finalScore = max(0, $finalScore - $taskSubmission->task->late_penalty);
        }

        // 3. Update TaskSubmission
        $taskSubmission->update([
            'score'       => $finalScore,
            'xp_earned'   => $validated['xp_earned'],
            'feedback'    => $validated['feedback'],
            'status'      => 'graded',
            'graded_at'   => now(),
        ]);

        // 4. Update pivot table
        $taskSubmission->student->tasks()
            ->updateExistingPivot($taskSubmission->task_id, [
                'status' => 'graded',
                'score'  => $finalScore,
            ]);

        // 5. Award XP to student
        $xpEngine->award(
            studentId: $taskSubmission->student_id,
            amount: $validated['xp_earned'],
            type: 'earned',
            source: 'task_completion',
            sourceId: $taskSubmission->task_id,
            description: "Earned {$validated['xp_earned']} XP for completing '{$taskSubmission->task->title}'"
        );

        return redirect()
            ->route('instructors.task-submissions.show', $taskSubmission)
            ->with('success', 'Submission graded and XP awarded successfully!');
    }

}
