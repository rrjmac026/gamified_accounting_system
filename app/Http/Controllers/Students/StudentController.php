<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;

class StudentController extends Controller
{
    // ✅ Only declare this once!
    protected function getAuthenticatedStudent()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'student') {
            abort(403, 'Unauthorized.');
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        return $student;
    }

    public function dashboard()
    {
        $student = $this->getAuthenticatedStudent();
        return view('students.dashboard', compact('student'));
    }

    public function viewProgress()
    {
        $student = $this->getAuthenticatedStudent()->load(['performanceLogs', 'quizScores', 'badges']);
        return view('students.progress', compact('student'));
    }

    public function viewAssignments()
    {
        $student = $this->getAuthenticatedStudent();
        $assignments = $student->assignedTasks()->with('subject')->orderByDesc('due_date')->paginate(10);
        return view('students.assignments', compact('student', 'assignments'));
    }

    public function viewXp()
    {
        $student = $this->getAuthenticatedStudent()->load('xpTransactions');
        return view('students.xp', compact('student'));
    }
}
