<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Subject;
use App\Models\Task;

class AdminController extends Controller
{
    /**
     * Admin dashboard statistics.
     */
    public function dashboard()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_instructors' => Instructor::count(),
            'total_subjects' => Subject::count(),
            'total_users' => User::count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    /**
     * List all users.
     */
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * List all students with their user info.
     */
    public function students()
    {
        $students = Student::with('user')->latest()->paginate(10);
        return view('admin.student.index', compact('students'));
    }

    /**
     * List all instructors with their user info.
     */
    public function instructors()
    {
        $instructors = Instructor::with('user')->latest()->paginate(10);
        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * List all subjects with their assigned instructors.
     */
    public function subjects()
    {
        $subjects = Subject::with('instructor')->latest()->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }

    /**
     * View all tasks (assignments, exercises, quizzes, etc.) created by instructors.
     */
    public function instructorAssignments()
    {
        $tasks = Task::with(['instructor.user', 'subject'])
                     ->latest()
                     ->paginate(10);

        return view('admin.assignments.index', compact('tasks'));
    }

    /**
     * XP & score settings (stub view).
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Reports & analytics (stub view).
     */
    public function reports()
    {
        return view('admin.reports');
    }
}
