<?php
namespace App\Observers;

use App\Models\TaskSubmission;
use App\Models\Grade;
use App\Models\Task;

class TaskSubmissionObserver
{
    public function created(TaskSubmission $submission)
    {
        $this->autoGrade($submission);
    }

    public function updated(TaskSubmission $submission)
    {
        if ($submission->isDirty('score')) {
            $this->updateFinalGrade($submission->student_id, $submission->task->subject_id);
        }
    }

    protected function autoGrade(TaskSubmission $submission)
    {
        $task = $submission->task;

        // Only auto-grade if the task is "auto-gradable"
        if ($task->is_auto_gradable) {
            $totalScore = $submission->score ?? 0;

            $submission->update([
                'score'     => $totalScore,
                'status'    => 'graded',
                'graded_at' => now(),
            ]);

            // ✅ Keep pivot in sync
            $submission->student->tasks()
                ->updateExistingPivot($submission->task_id, [
                    'status' => 'graded',
                    'score'  => $totalScore,
                    'graded_at' => now(),
                ]);

            $this->updateFinalGrade($submission->student_id, $task->subject_id);
        } else {
            // For manual tasks → just mark as "submitted" until instructor grades it
            $submission->update([
                'status' => 'submitted',
            ]);

            $submission->student->tasks()
                ->updateExistingPivot($submission->task_id, [
                    'status' => 'submitted',
                ]);
        }
    }




    protected function updateFinalGrade($studentId, $subjectId)
    {
        $subjectTasks = Task::where('subject_id', $subjectId)->get();

        // 🔎 Find the Final Project task
        $finalProject = $subjectTasks->firstWhere('is_final', true);

        if ($finalProject) {
            $finalSubmission = TaskSubmission::where('student_id', $studentId)
                ->where('task_id', $finalProject->id)
                ->first();

            // ❌ If Final Project is missing → INC
            if (!$finalSubmission) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'subject_id' => $subjectId,
                        'semester' => $finalProject->subject->semester ?? null,
                        'academic_year' => $finalProject->subject->academic_year ?? null,
                    ],
                    [
                        'final_grade' => null,
                        'remarks' => 'INC', // Incomplete due to missing Final Project
                    ]
                );
                return;
            }
        }

        // ✅ Otherwise calculate average grade
        $submissions = TaskSubmission::where('student_id', $studentId)
            ->whereHas('task', fn($q) => $q->where('subject_id', $subjectId))
            ->get();

        $average = $submissions->avg('score');
        $finalGrade = $this->convertToCollegeGrade($average);

        Grade::updateOrCreate(
            [
                'student_id' => $studentId,
                'subject_id' => $subjectId,
                'semester' => $subjectTasks->first()->subject->semester,
                'academic_year' => $subjectTasks->first()->subject->academic_year,
            ],
            [
                'final_grade' => $finalGrade,
                'remarks' => $finalGrade <= 3.0 ? 'Passed' : 'Failed',
            ]
        );
    }

    protected function convertToCollegeGrade($score)
    {
        if ($score >= 96) return 1.0;
        if ($score >= 94) return 1.25;
        if ($score >= 91) return 1.5;
        if ($score >= 88) return 1.75;
        if ($score >= 85) return 2.0;
        if ($score >= 82) return 2.25;
        if ($score >= 79) return 2.5;
        if ($score >= 76) return 2.75;
        if ($score >= 75) return 3.0;
        return 5.0;
    }
}
