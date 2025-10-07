<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\PerformanceTask;
use App\Models\PerformanceTaskSubmission;
use App\Models\PerformanceTaskAnswerSheet;
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
            if ($submission->exists && $submission->attempts >= 2) {
                return back()->with('error', 'You have reached the maximum of 2 attempts for this step.');
            }

            // 📝 Save student's data
            $studentData = $request->input('submission_data');
            
            // Decode and validate JSON
            $studentDataArray = json_decode($studentData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->with('error', 'Invalid submission data format.');
            }

            $submission->submission_data = $studentData;
            $submission->attempts = ($submission->attempts ?? 0) + 1;

            // 🔍 Fetch the correct answer sheet
            $answerSheet = PerformanceTaskAnswerSheet::where([
                'performance_task_id' => $task->id,
                'step' => $step
            ])->first();

            if ($answerSheet && $answerSheet->correct_data) {
                $correctData = $answerSheet->correct_data;
                
                // 🔧 FIXED: Handle if correct_data is still a string
                if (is_string($correctData)) {
                    $correctData = json_decode($correctData, true);
                }
                
                // Normalize both arrays for comparison (sort keys recursively)
                $normalizedStudent = $this->normalizeArray($studentDataArray);
                $normalizedCorrect = $this->normalizeArray($correctData);
                
                // 🔍 Compare normalized arrays
                if ($normalizedStudent === $normalizedCorrect) {
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

            // ✅ Feedback message
            $message = "Step $step saved successfully! (Attempt {$submission->attempts}/2)";

            // 🧭 If last step, go to dashboard
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
     * Normalize array for comparison (recursive ksort)
     */
    private function normalizeArray($array)
    {
        if (!is_array($array)) {
            return $array;
        }
        
        ksort($array);
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->normalizeArray($value);
            }
        }
        
        return $array;
    }

    public function submit()
    {
        return back()->with('success', 'Performance task submitted!');
    }
}