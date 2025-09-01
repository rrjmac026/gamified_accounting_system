<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Task;
use App\Models\Student;
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

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $instructors = $query->paginate(10);
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
        $instructor = Instructor::where('user_id', Auth::id())
            ->with('subjects.tasks', 'subjects.students')
            ->firstOrFail();

        $totalSubjects = $instructor->subjects->count();
        $totalTasks = $instructor->subjects->flatMap->tasks->count();
        $totalStudents = $instructor->subjects->flatMap->students->unique('id')->count();

        return view('instructors.dashboard', compact(
            'instructor',
            'totalSubjects',
            'totalTasks',
            'totalStudents'
        ));
    }

    /**
     * Show monthly activity statistics.
     */
    public function statistics()
    {
        $instructor = Instructor::where('user_id', Auth::id())->firstOrFail();
        $logs = ActivityLog::where('user_id', $instructor->user_id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();

        $dailyCounts = $logs->groupBy(function ($log) {
            return Carbon::parse($log->created_at)->format('Y-m-d');
        })->map->count();

        return view('instructors.statistics', compact('dailyCounts'));
    }

    /**
     * Search instructors by name/email.
     */
    public function search(Request $request)
    {
        $search = $request->input('query');
        $instructors = Instructor::whereHas('user', function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        })->get();

        return response()->json($instructors);
    }
}
