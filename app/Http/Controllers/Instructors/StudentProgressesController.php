<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentProgressesController extends Controller
{
    public function index(Request $request)
    {
        $instructor = auth()->user()->instructor;

        $students = Student::whereHas('sections', function($query) use ($instructor) {
            $query->whereHas('instructors', function($q) use ($instructor) {
                $q->where('instructors.id', $instructor->id);
            });
        })
        ->with(['user', 'course', 'sections', 'xpTransactions']) // <-- important!
        ->get();

        // Calculate XP & level from database for each student
        $students->map(function ($student) {
            $totalXp = $student->xpTransactions->sum('amount');
            $student->calculated_xp = $totalXp;
            $student->level = floor($totalXp / 1000) + 1;
            $student->xp_in_level = $totalXp % 1000;
            $student->progress_percentage = ($student->xp_in_level / 1000) * 100;
            return $student;
        });

        return view('instructors.progress.index', compact('students'));
    }



    public function show(Student $student)
    {
        // Load necessary relationships
        $student->load([
            'user',
            'course',
            'sections',
            'xpTransactions',
            'tasks' => function($query) {
                $query->orderBy('due_date', 'desc');
            },
            'tasks.subject',
            'badges'
        ]);

        // Calculate performance metrics
        $metrics = $this->calculateMetrics($student);
    

        // Calculate XP growth over time (weekly)
        $xpProgress = $student->xpTransactions()
            ->selectRaw('DATE(created_at) as date, SUM(amount) as daily_xp')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($record) {
                return [
                    'date' => $record->date,
                    'xp' => $record->daily_xp
                ];
            });

        return view('instructors.progress.show', compact(
            'student',
            'metrics',
            'xpProgress'
        ));
    }

    private function calculateMetrics(Student $student)
    {
        // Only main tasks
        $tasks = $student->tasks->where('parent_task_id', null);

        $totalTasks = $tasks->count();
        $completedTasks = $tasks->filter(function ($task) {
            $submission = $task->submissions->first();
            return strtolower($task->pivot->status) === 'submitted' || 
                ($submission && $submission->score !== null);
        })->count();

        // Average score for all tasks (including sub-tasks if needed)
        $averageScore = $student->tasks->filter(fn($task) => $task->pivot->score !== null)
                                    ->avg(fn($task) => $task->pivot->score) ?? 0;

        // XP
        $totalXp = $student->xpTransactions()->sum('amount');

        // Badges earned (both manual and by XP threshold)
        $badgesEarnedManual = $student->badges->count();
        $badgesEarnedAuto = \App\Models\Badge::where('is_active', true)
            ->where('xp_threshold', '<=', $totalXp)
            ->count();
        $totalBadgesEarned = max($badgesEarnedManual, $badgesEarnedAuto);

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'completion_rate' => $totalTasks ? round(($completedTasks / $totalTasks) * 100, 2) : 0,
            'average_score' => $averageScore,
            'on_time_submissions' => $tasks->where('pivot.was_late', false)->count(),
            'late_submissions' => $tasks->where('pivot.was_late', true)->count(),
            'total_xp' => $totalXp,
            'badges_earned' => $totalBadgesEarned,
            'class_rank' => $student->getLeaderboardRank(),
            'level' => floor($totalXp / 1000) + 1
        ];
    }

}
