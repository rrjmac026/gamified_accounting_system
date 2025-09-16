<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Student;
use App\Models\Section;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InstructorController extends Controller
{
    /**
     * Display a listing of instructors with filtering and pagination.
     */

    public function index(Request $request)
    {
        $query = Instructor::with('user');
        $sections = $instructor->sections()->with('students', 'subjects.tasks')->get();


        return view('instructors.index', compact('instructors'));
    }

    public function mySections()
    {
        $instructor = Auth::user()->instructor;
        $sections = $instructor->sections()->with('course', 'students')->get();

        return view('instructors.sections.index', compact('sections'));
    }

    /**
     * Show details of a specific instructor.
     */
    public function show(Instructor $instructor)
    {
        $instructor->load('user', 'subjects', 'subjects.tasks');
        return view('instructors.show', compact('instructor'));
    }

    /**
     * Instructor dashboard (overview of assigned subjects and stats).
     */
    public function dashboard()
    {
        $instructor = auth()->user()->instructor;
        
        // Load relationships
        $instructor->load([
            'sections.students',
            'subjects.tasks',
            'subjects.sections'
        ]);

        // Calculate statistics
        $stats = [
            'total_subjects' => $instructor->subjects->count(),
            'total_students' => $instructor->sections->flatMap->students->unique('id')->count(),
            'active_tasks' => $instructor->subjects->flatMap->tasks->where('is_active', true)->count(),
            'submissions_pending' => $instructor->subjects->flatMap->tasks->flatMap->submissions->where('status', 'pending')->count(),
        ];

        // Get recent submissions
        $recentSubmissions = Task::whereHas('subject.instructors', function($q) use ($instructor) {
            $q->where('instructor_id', $instructor->id);
        })
        ->with(['student.user', 'subject'])
        ->whereHas('submissions', function($q) {
            $q->where('status', 'pending');
        })
        ->latest()
        ->take(5)
        ->get();

        // Get upcoming tasks
        $upcomingTasks = $instructor->subjects
            ->flatMap->tasks
            ->where('due_date', '>', now())
            ->where('is_active', true)
            ->sortBy('due_date')
            ->take(5);

        // Performance overview
        $performanceData = $instructor->sections->map(function($section) {
            return [
                'section_name' => $section->name,
                'avg_score' => $section->students->avg(function($student) {
                    return $student->tasks->avg('pivot.score');
                }),
                'submission_rate' => $section->students->avg(function($student) {
                    $total = $student->tasks->count();
                    $submitted = $student->tasks->where('pivot.status', 'submitted')->count();
                    return $total ? ($submitted / $total * 100) : 0;
                })
            ];
        });

        return view('instructors.dashboard', compact(
            'instructor',
            'stats',
            'recentSubmissions',
            'upcomingTasks',
            'performanceData'
        ));
    }

}
