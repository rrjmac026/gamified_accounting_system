<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subject;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            // Get task parameter from route
            $taskId = $request->route('task');
            $task = null;
            
            // Get task instance if taskId exists
            if ($taskId) {
                if ($taskId instanceof Task) {
                    $task = $taskId;
                } else {
                    $task = Task::find($taskId);
                }

                // If task exists, check ownership
                if ($task) {
                    $currentInstructorId = Auth::user()->instructor->id;
                    if ($task->instructor_id !== $currentInstructorId) {
                        abort(403, 'Unauthorized access to this task');
                    }
                }
            }
            
            return $next($request);
        })->except(['create', 'store']);
    }

    public function index()
    {
        // Get current instructor's ID
        $instructorId = Auth::user()->instructor->id;
        
        // Only fetch tasks belonging to the current instructor
        $tasks = Task::with(['subject', 'instructor'])
            ->where('instructor_id', $instructorId)
            ->get();

        return view('instructors.tasks.index', compact('tasks'));
    }

    public function create()
    {
        // Get the currently authenticated instructor
        $instructor = Auth::user()->instructor;
        
        // Get only the subjects assigned to this instructor
        $subjects = $instructor->subjects()->with('section')->get();
        
        // Get sections where this instructor is assigned
        $sections = $instructor->sections;

        return view('instructors.tasks.create', compact('subjects', 'sections'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exercise,quiz,project',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'retry_limit' => 'required|integer|min:1',
            'late_penalty' => 'nullable|integer|min:0',
            'max_score' => 'required|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'instructions' => 'required|string',
            'is_active' => 'required|boolean',
            'auto_grade' => 'required|boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:10240',
            'allow_late_submission' => 'sometimes|boolean',

            
        ]);
        $validated['allow_late_submission'] = $request->has('allow_late_submission');

        $validated['instructor_id'] = Auth::user()->instructor->id;

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('task_attachments', 'public');
            $validated['attachment'] = $path;
        }

        // Create task
        $task = Task::create($validated);

        // Auto-assign to section students
        $section = \App\Models\Section::with('students')->findOrFail($validated['section_id']);
        $attachData = [];
        foreach ($section->students as $student) {
            $attachData[$student->id] = [
                'status' => 'assigned',
                'due_date' => \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        if (!empty($attachData)) {
            $task->students()->attach($attachData);
        }

        return redirect()->route('instructors.tasks.index')
            ->with('success', 'Task created (with attachment if uploaded) and assigned to students.');
    }



    public function show(Task $task)
    {
        $task->load([
            'subject',
            'instructor.user',
            'submissions',
            'questions',
            'students.user'
        ]);
        
        return view('instructors.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $instructor = Auth::user()->instructor;
        $subjects = $instructor->subjects;
        $sections = $instructor->sections;
        return view('instructors.tasks.edit', compact('task', 'subjects','sections'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exercise,quiz,project',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'retry_limit' => 'required|integer|min:1',
            'late_penalty' => 'nullable|integer|min:0',
            'max_score' => 'required|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'instructions' => 'required|string',
            'is_active' => 'required|boolean',
            'auto_grade' => 'required|boolean',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,png|max:10240',
            'allow_late_submission' => 'sometimes|boolean',
        ]);

        $validated['allow_late_submission'] = $request->boolean('allow_late_submission');


        // Handle file upload
        if ($request->hasFile('attachment')) {
            // Delete old file if exists
            if ($task->attachment && \Storage::disk('public')->exists($task->attachment)) {
                \Storage::disk('public')->delete($task->attachment);
            }

            // Save new file
            $path = $request->file('attachment')->store('task_attachments', 'public');
            $validated['attachment'] = $path;
        }

        // If user wants to remove the file (optional)
        if ($request->has('remove_attachment') && $request->remove_attachment == '1') {
            if ($task->attachment && \Storage::disk('public')->exists($task->attachment)) {
                \Storage::disk('public')->delete($task->attachment);
            }
            $validated['attachment'] = null;
        }

        $task->update($validated);

        return redirect()->route('instructors.tasks.index')
            ->with('success', 'Task updated successfully');
    }


    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('instructors.tasks.index')
            ->with('success', 'Task deleted successfully');
    }


    public function showAssignStudentsForm(Task $task)
    {
        $students = Student::all(); // Or filter by course/subject
        return view('instructors.tasks.assign-students', compact('task', 'students'));
    }
       // === QUESTION MANAGEMENT METHODS ===
    
    public function addQuestion(Request $request, Task $task)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay,calculation',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        // Create a new task record that represents a question
        $questionData = array_merge($validated, [
            'title' => 'Question for: ' . $task->title,
            'type' => 'question', // Add this type to your enum
            'subject_id' => $task->subject_id,
            'instructor_id' => $task->instructor_id,
            'parent_task_id' => $task->id, // Add this field to track parent
        ]);

        Task::create($questionData);

        return redirect()->route('instructors.tasks.show', $task)
            ->with('success', 'Question added successfully');
    }

    public function editQuestion(Task $task, Task $question)
    {
        return view('instructors.tasks.edit-question', compact('task', 'question'));
    }

    public function updateQuestion(Request $request, Task $task, Task $question)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay,calculation',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        $question->update($validated);
        
        return redirect()->route('instructors.tasks.show', $task)
            ->with('success', 'Question updated successfully');
    }

    public function deleteQuestion(Task $task, Task $question)
    {
        $question->delete();
        
        return redirect()->route('instructors.tasks.show', $task)
            ->with('success', 'Question deleted successfully');
    }

    // === STUDENT TASK ASSIGNMENT METHODS ===1
    
    public function assignToStudent(Request $request, Task $task)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:assigned,in_progress,submitted,graded,overdue',
            'due_date' => 'nullable|date',
        ]);

        
        if ($task->students()->where('student_id', $validated['student_id'])->exists()) {
            return back()->with('error', 'Task already assigned to this student');
        }

        $task->students()->attach($validated['student_id'], [
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ? 
                \Carbon\Carbon::parse($validated['due_date'])->format('Y-m-d H:i:s') : 
                \Carbon\Carbon::parse($task->due_date)->format('Y-m-d H:i:s'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        return redirect()->route('instructors.tasks.show', $task)
            ->with('success', 'Task assigned to student successfully');
    }

    public function bulkAssign(Request $request)
    {
        $instructor = Auth::user()->instructor;
        
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'student_ids' => [
                'required',
                'array',
                function($attribute, $value, $fail) use ($instructor) {
                    // Verify all students belong to instructor's sections
                    $validStudents = Student::whereIn('id', $value)
                        ->whereHas('sections', function($query) use ($instructor) {
                            $query->whereHas('instructors', function($q) use ($instructor) {
                                $q->where('instructors.id', $instructor->id);
                            });
                        })->count();
                        
                    if(count($value) !== $validStudents) {
                        $fail('Some selected students are not in your sections.');
                    }
                }
            ],
            'status' => 'required|in:assigned,in_progress,submitted,graded,overdue',
        ]);

        $task = Task::find($validated['task_id']);
        $attachData = [];

        foreach ($validated['student_ids'] as $studentId) {
            if (!$task->students()->where('student_id', $studentId)->exists()) {
                $attachData[$studentId] = [
                    'status' => $validated['status'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $task->students()->attach($attachData);

        $assignedCount = count($attachData);
        return redirect()->route('instructors.tasks.show', $task)
            ->with('success', "Task assigned to {$assignedCount} students successfully");
    }

    public function studentTasks()
    {
        // Show all task-student relationships
        $tasks = Task::with(['subject', 'instructor'])
                    ->whereHas('students')
                    ->mainTasks() // Only main tasks, not questions
                    ->paginate(10);
        
        return view('instructors.tasks.student-tasks', compact('tasks'));
    }

    public function showStudentTask(Task $task, Student $student)
    {
        $task->load(['subject', 'instructor']);
        $studentTaskData = $task->students()
                               ->where('student_id', $student->id)
                               ->first();
        
        if (!$studentTaskData) {
            abort(404, 'Student task assignment not found');
        }

        return view('instructors.tasks.show-student-task', compact('task', 'student', 'studentTaskData'));
    }

    public function gradeStudentForm(Task $task, Student $student)
    {
        $studentTaskData = $task->students()
                               ->where('student_id', $student->id)
                               ->first();
        
        if (!$studentTaskData) {
            abort(404, 'Student task assignment not found');
        }

        return view('instructors.tasks.grade-student', compact('task', 'student', 'studentTaskData'));
    }

    public function gradeStudent(Request $request, Task $task, Student $student)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'xp_earned' => 'required|integer|min:0',
        ]);

        $task->students()->updateExistingPivot($student->id, [
            'score' => $validated['score'],
            'xp_earned' => $validated['xp_earned'],
            'status' => 'graded',
            'graded_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('instructors.tasks.show-student-task', [$task, $student])
            ->with('success', 'Student graded successfully');
    }

}