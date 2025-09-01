<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\FeedbackRecord;
use App\Http\Requests\FeedbackRecordRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class FeedbackController extends Controller
{
    /**
     * Show the student's submitted feedback records.
     */
    public function index()
    {
        $feedbacks = FeedbackRecord::with(['task'])
            ->where('student_id', Auth::id()) // only this student's feedback
            ->latest()
            ->paginate(10);

        return view('students.feedback.index', compact('feedbacks'));
    }

    public function create()
    {
        $tasks = Task::all(); // Fetch tasks for feedback association
        return view('students.feedback.create', compact('tasks'));
    }

    /**
     * Store a new feedback record from student.
     */
    public function store(FeedbackRecordRequest $request)
    {
        FeedbackRecord::create([
            'student_id' => Auth::user()->student->id, // ensure it belongs to the logged-in student
            'task_id'    => $request->task_id,
            'content'    => $request->content,
            'rating'     => $request->rating ?? null, // optional rating if you allow it
        ]);

        return redirect()->route('students.feedback.index')
                 ->with('success', 'Your feedback has been submitted.');
    }
}
