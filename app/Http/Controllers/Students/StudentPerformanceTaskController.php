<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\PerformanceTask;
use App\Models\PerformanceTaskSubmission;
use Illuminate\Http\Request;

class StudentPerformanceTaskController extends Controller
{
    // Redirect index -> Step 1
    public function index()
    {
        return redirect()->route('students.performance-tasks.step', 1);
    }

    public function step($step)
    {
        $user = auth()->user();
        
        $performanceTask = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->latest()
        ->first();

        if (!$performanceTask) {
            return redirect()->route('students.dashboard')
                ->with('error', 'No active performance task found.');
        }

        // Get the current submission for this step
        $submission = PerformanceTaskSubmission::where([
            'task_id' => $performanceTask->id,
            'student_id' => $user->student->id,
            'step' => $step
        ])->first();

        // Get all submissions to check progress
        $submissions = PerformanceTaskSubmission::where([
            'task_id' => $performanceTask->id,
            'student_id' => $user->student->id,
        ])->pluck('step')->toArray();

        // Only check for previous step completion if not step 1
        if ($step > 1 && !in_array($step - 1, $submissions)) {
            return redirect()->route('students.performance-tasks.step', $step - 1)
                ->with('error', "You must complete Step " . ($step - 1) . " first.");
        }

        return view("students.performance-tasks.step-$step", [
            'performanceTask' => $performanceTask,
            'submission' => $submission,
            'completedSteps' => $submissions
        ]);
    }

    public function saveStep(Request $request, $step)
    {
        $user = auth()->user();

        // Get the current performance task of the student's section
        $task = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->latest()
        ->first();

        if (!$task) {
            return back()->with('error', 'No active performance task found.');
        }

        try {
            // 🔍 Check existing submission
            $submission = PerformanceTaskSubmission::firstOrNew([
                'task_id' => $task->id,
                'student_id' => $user->student->id,
                'step' => $step,
            ]);

            // 🚫 Stop if already reached 2 attempts
            if ($submission->attempts >= 2) {
                return back()->with('error', 'You have reached the maximum of 2 attempts for this step.');
            }

            // 📝 Save submission data (from Handsontable or JSON)
            $submission->submission_data = $request->input('submission_data');
            $submission->status = 'in-progress';
            $submission->attempts = $submission->attempts + 1;
            $submission->save();

            // ✅ Feedback
            $message = "Step $step saved successfully! (Attempt {$submission->attempts}/2)";

            // 🧭 If last step, redirect to dashboard
            if ($step >= 10) {
                return redirect()->route('students.dashboard')
                    ->with('success', 'You have successfully completed all 10 steps of the performance task!');
            }

            // Otherwise go to next step
            return redirect()->route('students.performance-tasks.step', $step + 1)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Error saving your submission. Please try again.');
        }
    }



    public function submit()
    {
        return back()->with('success', 'Performance task submitted!');
    }
}
