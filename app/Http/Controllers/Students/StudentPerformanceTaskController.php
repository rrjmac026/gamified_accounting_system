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
        });

        return view('students.performance-tasks.index', compact('performanceTasks'));
    }

    /**
     * Show progress page for the most recent active performance task
     */
    public function progress($taskId = null)
    {
        $user = auth()->user();

        // Find the selected performance task
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

        // Fetch steps already completed
        $completedSteps = PerformanceTaskSubmission::where([
            'task_id' => $performanceTask->id,
            'student_id' => $user->student->id,
        ])->pluck('step')->toArray();

        return view('students.performance-tasks.progress', compact('performanceTask', 'completedSteps'));
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
        ]);
    }


    /**
     * Save or retry a step submission
     */
    public function saveStep(Request $request, $step)
    {
        $user = auth()->user();

        $task = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->latest()
        ->first();

        if (!$task) {
            return back()->with('error', 'No active performance task found.');
        }

        try {
            // Check existing submission
            $submission = PerformanceTaskSubmission::firstOrNew([
                'task_id' => $task->id,
                'student_id' => $user->student->id,
                'step' => $step,
            ]);

            // Limit attempts
            if ($submission->exists && $submission->attempts >= 2) {
                return back()->with('error', 'You have reached the maximum of 2 attempts for this step.');
            }

            // Save student's data
            $studentData = $request->input('submission_data');
            
            // Decode JSON
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
                $correctData = $answerSheet->correct_data;

                if (is_string($correctData)) {
                    $correctData = json_decode($correctData, true);
                }

                $isCorrect = $this->compareAnswers($studentDataArray, $correctData);

                if ($isCorrect) {
                    $submission->status = 'correct';
                    $submission->score = 100;
                    $submission->remarks = 'Perfect! Your entry is correct.';
                } else {
                    $submission->status = 'wrong';
                    $submission->score = 0;
                    $submission->remarks = 'Your answer is incorrect. Please review and retry.';
                }
            } else {
                $submission->status = 'in-progress';
                $submission->remarks = 'Answer sheet not found for this step.';
            }

            $submission->save();

            $message = "Step $step saved successfully! (Attempt {$submission->attempts}/2) - Status: " . ucfirst($submission->status);

            // If last step
            if ($step >= 10) {
                return redirect()->route('students.dashboard')
                    ->with('success', 'You have successfully completed all 10 steps of the performance task!');
            }

            // Otherwise go to next step
            return redirect()->route('students.performance-tasks.step', $step + 1)
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Performance Task Submission Error: ' . $e->getMessage());
            return back()->with('error', 'Error saving your submission. Please try again.');
        }
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
