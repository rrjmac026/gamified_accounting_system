<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Http\Requests\EvaluationRequest;
use Illuminate\Support\Facades\Auth;

class EvaluationController extends Controller
{
    public function __construct()
    {
        // Students can only create/store (and optionally view their own)
        $this->middleware('role:student')->only(['create', 'store', 'myEvaluations']);

        // Admins/Instructors can list, view, delete
        $this->middleware('role:admin,instructor')->only(['index', 'show', 'destroy']);
    }

    /**
     * Show all evaluations (Admin/Instructor only)
     */
    public function index()
    {
        $evaluations = Evaluation::with(['student', 'instructor', 'course'])
            ->latest('submitted_at')
            ->paginate(15);

        return view('admin.evaluations.index', compact('evaluations'));
    }

    /**
     * Show create form (Student only)
     */
    public function create()
    {
        return view('evaluations.create'); // separate student view
    }

    /**
     * Store a new evaluation (Student only)
     */
    public function store(EvaluationRequest $request)
    {
        Evaluation::create([
            'student_id'    => Auth::user()->student->id,
            'instructor_id' => $request->instructor_id,
            'course_id'     => $request->course_id,
            'responses'     => $request->responses,
            'comments'      => $request->comments,
            'submitted_at'  => now(),
        ]);

        return redirect()->route('students.my-evaluations')
            ->with('success', 'Your evaluation has been submitted.');
    }

    /**
     * Show a single evaluation (Admin/Instructor only)
     */
    public function show(Evaluation $evaluation)
    {
        $evaluation->load(['student', 'instructor', 'course']);
        return view('admin.evaluations.show', compact('evaluation'));
    }

    /**
     * Show logged-in student's evaluations (Student only)
     */
    public function myEvaluations()
    {
        $evaluations = Evaluation::where('student_id', Auth::user()->student->id)
            ->with(['instructor', 'course'])
            ->latest('submitted_at')
            ->paginate(10);

        return view('students.evaluations.index', compact('evaluations'));
    }

    /**
     * Delete evaluation (Admin only)
     */
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return redirect()->route('admin.evaluations.index')
            ->with('success', 'Evaluation deleted successfully.');
    }
}
