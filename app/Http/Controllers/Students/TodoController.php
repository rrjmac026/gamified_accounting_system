<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        
        // Get all tasks assigned to student with their relationships
        $tasks = $student->tasks()
        ->with(['subject', 'submissions' => function ($query) use ($student) {
            $query->where('student_id', $student->id)
                ->latest('submitted_at');
        }])
        ->get();


        $groupedTasks = $tasks->groupBy(function($task) {
            $submission = $task->submissions->first();
            if ($submission && $submission->score !== null) {
                return 'graded';
            }
            return $task->pivot->status;
        });

        return view('students.todo.index', compact('groupedTasks'));
    }
}

