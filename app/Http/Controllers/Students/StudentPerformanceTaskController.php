<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\PerformanceTask;
use App\Models\PerformanceTaskSubmission;
use App\Models\PerformanceTaskAnswerSheet;
use Illuminate\Http\Request;

class StudentPerformanceTaskController extends Controller
{
    /**
     * Show list of performance tasks assigned to the logged-in student
     */
    public function index()
    {
        $user = auth()->user();

        // Fetch all tasks assigned to the student's section with relationships
        $performanceTasks = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->with([
            'section',
            'subject',
            'instructor'
        ])
        ->latest()
        ->get();

        // Calculate progress for each task
        $performanceTasks->each(function ($task) use ($user) {
            // Count unique completed steps (regardless of correct/wrong status)
            $completedSteps = PerformanceTaskSubmission::where('task_id', $task->id)
                ->where('student_id', $user->student->id)
                ->distinct()
                ->pluck('step')
                ->unique()
                ->count();
            
            $task->progress = $completedSteps;
            $task->totalSteps = 10;
            $task->progressPercentage = ($completedSteps > 0) ? round(($completedSteps / 10) * 100, 2) : 0;
            
            // Add deadline status
            $task->deadlineStatus = $this->getDeadlineStatus($task);
        });

        return view('students.performance-tasks.index', compact('performanceTasks'));
    }

    /**
     * Show progress page for the most recent active performance task
     */
    public function progress($taskId = null)
    {
        $user = auth()->user();

        $performanceTask = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })
            ->when($taskId, function ($query) use ($taskId) {
                $query->where('id', $taskId);
            })
            ->latest()
            ->first();

        if (!$performanceTask) {
            return redirect()->route('students.performance-tasks.index')
                ->with('error', 'Performance task not found or not assigned to you.');
        }

        // Check if submissions are still allowed
        $deadlineStatus = $this->getDeadlineStatus($performanceTask);
        if ($deadlineStatus['canSubmit'] === false) {
            return redirect()->route('students.performance-tasks.index')
                ->with('error', $deadlineStatus['message']);
        }

        $completedSteps = PerformanceTaskSubmission::where([
            'task_id' => $performanceTask->id,
            'student_id' => $user->student->id,
        ])->pluck('step')->toArray();

        return view('students.performance-tasks.progress', compact('performanceTask', 'completedSteps', 'deadlineStatus'));
    }

    /**
     * Load the step view (e.g. step-1, step-2, ...)
     */
    public function step($id, $step)
    {
        $user = auth()->user();

        abort_if($step < 1 || $step > 10, 404);

        // Find the performance task and ensure it belongs to the student's section
        $performanceTask = PerformanceTask::where('id', $id)
            ->whereHas('section.students', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })
            ->first();

        if (!$performanceTask) {
            return redirect()->route('students.performance-tasks.index')
                ->with('error', 'No active performance task found.');
        }

        // Check deadline restrictions before allowing step access
        $deadlineStatus = $this->getDeadlineStatus($performanceTask);
        if ($deadlineStatus['canSubmit'] === false) {
            return redirect()->route('students.performance-tasks.index')
                ->with('error', $deadlineStatus['message']);
        }

        // Check if the student has a submission for this step
        $submission = PerformanceTaskSubmission::where([
            'task_id' => $performanceTask->id,
            'student_id' => $user->student->id,
            'step' => $step,
        ])->first();

        // Get the answer sheet template for this step
        $answerSheet = PerformanceTaskAnswerSheet::where([
            'performance_task_id' => $performanceTask->id,
            'step' => $step,
        ])->first();

        // Get all completed steps by the student
        $completedSteps = PerformanceTaskSubmission::where([
            'task_id' => $performanceTask->id,
            'student_id' => $user->student->id,
        ])->pluck('step')->toArray();

        // Prevent skipping steps
        if ($step > 1 && !in_array($step - 1, $completedSteps)) {
            return redirect()->route('students.performance-tasks.step', [
                'id' => $performanceTask->id,
                'step' => $step - 1,
            ])->with('error', "You must complete Step " . ($step - 1) . " first.");
        }

        return view("students.performance-tasks.step-$step", [
            'performanceTask' => $performanceTask,
            'submission' => $submission,
            'answerSheet' => $answerSheet,
            'completedSteps' => $completedSteps,
            'deadlineStatus' => $deadlineStatus,
        ]);
    }

    /**
     * Save or retry a step submission - FIXED METHOD SIGNATURE
     */
    public function saveStep(Request $request, $id, $step) // Added $id parameter
    {
        $user = auth()->user();

        // Find the specific performance task by ID (not just the latest)
        $task = PerformanceTask::where('id', $id)
            ->whereHas('section.students', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })
            ->first();

        if (!$task) {
            return back()->with('error', 'Performance task not found or not assigned to you.');
        }

        // CRITICAL: Check deadline restrictions
        $deadlineStatus = $this->getDeadlineStatus($task);
        if ($deadlineStatus['canSubmit'] === false) {
            return back()->with('error', $deadlineStatus['message']);
        }

        try {
            // Validate submission data
            $validated = $request->validate([
                'submission_data' => 'required|string'
            ]);

            // Check or create submission record
            $submission = PerformanceTaskSubmission::firstOrNew([
                'task_id' => $task->id,
                'student_id' => $user->student->id,
                'step' => $step,
            ]);

            // Limit attempts
            if ($submission->exists && $submission->attempts >= 2) {
                return back()->with('error', 'You have reached the maximum of 2 attempts for this step.');
            }

            // Decode student's submission data
            $studentData = $validated['submission_data'];
            $studentDataArray = json_decode($studentData, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid submission data format.');
            }

            $submission->submission_data = $studentData;
            $submission->attempts = ($submission->attempts ?? 0) + 1;

            // Fetch correct answers
            $answerSheet = PerformanceTaskAnswerSheet::where([
                'performance_task_id' => $task->id,
                'step' => $step
            ])->first();

            if ($answerSheet && $answerSheet->correct_data) {
                $correctData = is_string($answerSheet->correct_data)
                    ? json_decode($answerSheet->correct_data, true)
                    : $answerSheet->correct_data;

                $isCorrect = $this->compareAnswers($studentDataArray, $correctData);

                if ($isCorrect) {
                    $submission->status = 'correct';
                    $submission->score = 100;
                    $submission->remarks = $deadlineStatus['isLate']
                        ? 'Perfect! Your entry is correct but submitted late.'
                        : 'Perfect! Your entry is correct.';
                } else {
                    $submission->status = 'wrong';
                    $submission->score = 0;
                    $submission->remarks = $deadlineStatus['isLate']
                        ? 'Incorrect. Also note this submission is late.'
                        : 'Your answer is incorrect. Please review and retry.';
                }
            } else {
                $submission->status = 'in-progress';
                $submission->remarks = 'Answer sheet not found for this step.';
            }

            $submission->save();

            $message = "Step $step saved successfully! (Attempt {$submission->attempts}/2) - Status: " . ucfirst($submission->status); 

            if ($deadlineStatus['isLate']) {
                $message .= " ⚠️ Late submission - penalties may apply.";
            }

            if ($step >= 10) {
                return redirect()->route('students.performance-tasks.index')
                    ->with('success', 'You have successfully completed all 10 steps of the performance task!');
            }

            return redirect()->route('students.performance-tasks.step', [
                'id' => $task->id,
                'step' => $step + 1,
            ])->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Performance Task Submission Error: ' . $e->getMessage());
            return back()->with('error', 'Error saving your submission. Please try again.');
        }
    }

    /**
     * Get deadline status for a task
     * Returns: canSubmit (bool), isLate (bool), message (string), status (string)
     */
    private function getDeadlineStatus($task)
    {
        $now = now();
        
        // If no due dates are set, allow submissions
        if (!$task->due_date) {
            return [
                'canSubmit' => true,
                'isLate' => false,
                'status' => 'open',
                'message' => 'No deadline set for this task.',
            ];
        }

        // Check if past the late deadline (hard cutoff)
        if ($task->late_until && $now->greaterThan($task->late_until)) {
            return [
                'canSubmit' => false,
                'isLate' => true,
                'status' => 'closed',
                'message' => 'This task is no longer accepting submissions. The deadline has passed.',
            ];
        }

        // Check if between due_date and late_until (late but allowed)
        if ($task->late_until && $now->greaterThan($task->due_date) && $now->lessThanOrEqualTo($task->late_until)) {
            $hoursRemaining = $now->diffInHours($task->late_until);
            return [
                'canSubmit' => true,
                'isLate' => true,
                'status' => 'late',
                'message' => "⚠️ Late submission period. Final deadline: {$task->late_until->format('M d, Y h:i A')} ({$hoursRemaining} hours remaining)",
            ];
        }

        // Check if past due_date but no late_until is set (hard deadline)
        if (!$task->late_until && $now->greaterThan($task->due_date)) {
            return [
                'canSubmit' => false,
                'isLate' => true,
                'status' => 'closed',
                'message' => 'This task is past the due date and no longer accepting submissions.',
            ];
        }

        // Before due date (on time)
        $hoursRemaining = $now->diffInHours($task->due_date);
        return [
            'canSubmit' => true,
            'isLate' => false,
            'status' => 'on-time',
            'message' => "Due: {$task->due_date->format('M d, Y h:i A')} ({$hoursRemaining} hours remaining)",
        ];
    }

    /** 
     * Compare cell-by-cell answers
     */
    private function compareAnswers($studentData, $correctData)
    {
        if (!is_array($studentData) || !is_array($correctData)) return false;

        foreach ($correctData as $rowIndex => $correctRow) {
            if (!isset($studentData[$rowIndex])) return false;

            $studentRow = $studentData[$rowIndex];
            foreach ($correctRow as $colIndex => $correctValue) {
                if ($correctValue === null || $correctValue === '' || $correctValue === 0) continue;

                $studentValue = $studentRow[$colIndex] ?? null;

                if (!$this->valuesMatch($studentValue, $correctValue)) return false;
            }
        }

        return true;
    }

    private function valuesMatch($value1, $value2)
    {
        return $this->normalizeValue($value1) === $this->normalizeValue($value2);
    }

    private function normalizeValue($value)
    {
        if ($value === null || $value === '' || $value === 0) return '';
        if (is_numeric($value)) return number_format((float)$value, 2, '.', '');
        if (is_string($value)) return strtolower(trim($value));
        return (string)$value;
    }

    public function submit()
    {
        return back()->with('success', 'Performance task submitted!');
    }

    public function show($id)
    {
        $user = auth()->user();
        
        // Verify the task belongs to the student
        $task = PerformanceTask::where('id', $id)
            ->whereHas('section.students', function ($query) use ($user) {
                $query->where('student_id', $user->student->id);
            })
            ->firstOrFail();

        // Redirect to progress page with the task ID
        return redirect()->route('students.performance-tasks.progress', ['taskId' => $id]);
    }
}