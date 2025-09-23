<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\XpEngine;
use Carbon\Carbon;

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

        // 1️⃣ Validate input
        $validated = $request->validate([
            'score'     => 'required|numeric|min:0',
            'xp_earned' => 'required|integer|min:0', // Base XP before deductions
            'feedback'  => 'required|string',
        ]);

        $baseXp = $validated['xp_earned'];
        $finalScore = $validated['score'];
        $today = now();

        // 2️⃣ Ensure deadlines are Carbon instances
        $deadline = Carbon::parse($taskSubmission->task->deadline);
        $allowedLate = Carbon::parse($taskSubmission->task->allowed_late_date);

        // 3️⃣ Calculate XP after late deductions
        if ($today->greaterThan($allowedLate)) {
            // Past allowed late date → no XP
            $xpToAward = 0;
        } elseif ($today->greaterThan($deadline)) {
            // Late submission → deduct 10 XP per day late
            $daysLate = $today->diffInDays($deadline);
            $xpToAward = max($baseXp - ($daysLate * 10), 0);
            // Optional: reduce score proportionally
            $finalScore = max(0, $finalScore - ($baseXp - $xpToAward));
        } else {
            // On time
            $xpToAward = $baseXp;
        }

        // 4️⃣ Update TaskSubmission
        $taskSubmission->update([
            'score'     => $finalScore,
            'xp_earned' => $xpToAward,
            'feedback'  => $validated['feedback'],
            'status'    => 'graded',
            'graded_at' => now(),
        ]);

        // 5️⃣ Update pivot table (if student-task relationship tracks status/score/XP)
        $taskSubmission->student->tasks()
            ->updateExistingPivot($taskSubmission->task_id, [
                'status'    => 'graded',
                'score'     => $finalScore,
                'xp_earned' => $xpToAward,
            ]);

        // 6️⃣ Award XP via XpEngine
        $xpEngine->award(
            studentId: $taskSubmission->student_id,
            amount: $xpToAward,
            type: 'earned',
            source: 'task_completion',
            sourceId: $taskSubmission->task_id,
            description: "Earned {$xpToAward} XP for completing '{$taskSubmission->task->title}'"
        );

        // 7️⃣ Redirect back with success message
        return redirect()
            ->route('instructors.task-submissions.show', $taskSubmission)
            ->with('success', 'Submission graded and XP awarded successfully!');
    }



}
