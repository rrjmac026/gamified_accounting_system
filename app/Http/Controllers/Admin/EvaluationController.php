<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Course;
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
        $student = Auth::user()->student;
        
        // Get all instructors (or filter by student's enrolled courses)
        $instructors = Instructor::with('user')->get();
        
        // Get all courses the student is enrolled in
        // Assuming you have a many-to-many relationship between students and courses
        $courses = $student->courses ?? collect();
        
        // If no courses found, get all available courses
        if ($courses->isEmpty()) {
            $courses = \App\Models\Course::all();
        }
        
        // Example criteria (you can fetch from DB instead)
        $criteria = [
            'teaching_effectiveness' => 'How effective was the instructor\'s teaching?',
            'subject_knowledge' => 'How knowledgeable was the instructor about the subject?',
            'communication_clarity' => 'How clear was the instructor\'s communication?',
            'student_engagement' => 'How well did the instructor engage students?',
            'grading_fairness' => 'How fair was the instructor\'s grading?',
            'learning_materials' => 'How effective were the learning materials used?',
            'availability_support' => 'How available was the instructor for support?',
            'overall_satisfaction' => 'Overall satisfaction with the course'
        ];

        return view('students.evaluations.create', compact('instructors', 'courses', 'criteria'));
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

        return redirect()->route('students.evaluations.index')
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
