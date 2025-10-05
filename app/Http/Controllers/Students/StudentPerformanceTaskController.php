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
        
        // Get the current performance task
        $task = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->latest()
        ->first();

        if (!$task) {
            return back()->with('error', 'No active performance task found.');
        }

        try {
            // Save submission
            $submission = PerformanceTaskSubmission::updateOrCreate(
                [
                    'task_id' => $task->id,
                    'student_id' => $user->student->id,
                    'step' => $step,
                ],
                [
                    'submission_data' => $request->template_data,
                    'status' => 'in-progress'
                ]
            );

            return redirect()->route('students.performance-tasks.step', $step + 1)
                ->with('success', "Step $step saved successfully!");

        } catch (\Exception $e) {
            return back()->with('error', 'Error saving your submission. Please try again.');
        }
    }





    // Final submit
    public function submit()
    {
        return back()->with('success', 'Performance task submitted!');
    }
}
