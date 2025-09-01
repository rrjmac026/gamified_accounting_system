<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Subject;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['subject', 'instructor'])->get();
        return view('instructors.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $instructor = Auth::user()->instructor;
        $subjects = $instructor->subjects;
        
        // Get only students from sections where this instructor teaches
        $students = Student::whereHas('sections', function($query) use ($instructor) {
            $query->whereHas('instructors', function($q) use ($instructor) {
                $q->where('instructors.id', $instructor->id);
            });
        })->with('user')->get();

        return view('instructors.tasks.create', compact('subjects', 'students', 'instructor',));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exercise,quiz,project',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty_level' => 'required|integer|between:1,5',
            'retry_limit' => 'required|integer|min:1',
            'late_penalty' => 'nullable|integer|min:0',
            'max_score' => 'required|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'instructions' => 'required|string',
            'is_active' => 'required|boolean',
            'auto_grade' => 'required|boolean'
        ]);

        $validated['instructor_id'] = Auth::user()->instructor->id;
        $task = Task::create($validated); // <-- assign to $task

        if ($request->filled('student_ids')) {
            $task->students()->attach($request->student_ids, [
                'status' => $request->status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('instructors.tasks.index')
            ->with('success', 'Task created successfully');
    }

    public function show(Task $task)
    {
        $task->load(['subject', 'instructor', 'submissions', 'questions']);
        return view('instructors.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $instructor = Auth::user()->instructor;
        $subjects = $instructor->subjects;
        return view('instructors.tasks.edit', compact('task', 'subjects'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exercise,quiz,project',
            'subject_id' => 'required|exists:subjects,id',
            'difficulty_level' => 'required|integer|between:1,5',
            'retry_limit' => 'required|integer|min:1',
            'late_penalty' => 'nullable|integer|min:0',
            'max_score' => 'required|integer|min:0',
            'xp_reward' => 'required|integer|min:0',
            'due_date' => 'required|date',
            'instructions' => 'required|string',
            'is_active' => 'required|boolean',
            'auto_grade' => 'required|boolean'
        ]);

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

    // === STUDENT TASK ASSIGNMENT METHODS ===
    
    public function assignToStudent(Request $request, Task $task)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:assigned,in_progress,submitted,graded,overdue',
            'due_date' => 'nullable|date',
        ]);

        // Check if already assigned
        if ($task->students()->where('student_id', $validated['student_id'])->exists()) {
            return back()->with('error', 'Task already assigned to this student');
        }

        $task->students()->attach($validated['student_id'], [
            'status' => $validated['status'],
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

    // === CSV UPLOAD METHODS ===

    public function csvUpload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        try {
            $path = $request->file('csv_file')->getRealPath();
            $csvData = $this->parseCsv($path);
            
            $validatedTasks = $this->validateCsvData($csvData);
            $createdCount = $this->createTasksFromCsv($validatedTasks);

            $successMessage = "Successfully uploaded and created {$createdCount} student task assignments!";
            
            $warnings = session('csv_warnings', []);
            $createdStudents = session('csv_created_students', []);
            
            if (!empty($createdStudents)) {
                $successMessage .= "\n\nNew student accounts created: " . count($createdStudents);
            }
            
            if (!empty($warnings)) {
                session(['upload_warnings' => $warnings]);
            }

            return redirect()->route('instructors.tasks.index')
                ->with('success', $successMessage)
                ->with('warnings', $warnings);

        } catch (\Exception $e) {
            return back()->withErrors(['csv_file' => 'Error processing CSV: ' . $e->getMessage()]);
        }
    }

    public function downloadCsvTemplate()
    {
        $filename = 'student_tasks_template.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'student_email',
                'task_id',
                'status',
                'score',
                'xp_earned',
                'submitted_at',
                'graded_at'
            ]);

            fputcsv($file, [
                'student@example.com',
                '1',
                'assigned',
                '',
                '',
                '',
                ''
            ]);

            fputcsv($file, [
                'student2@example.com',
                '2',
                'submitted',
                '85',
                '50',
                '2024-03-15 14:30:00',
                ''
            ]);

            fputcsv($file, [
                'student3@example.com',
                '1',
                'graded',
                '92',
                '75',
                '2024-03-14 16:45:00',
                '2024-03-16 09:20:00'
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function parseCsv($filePath)
    {
        $csvData = [];
        $header = null;

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                if (!$header) {
                    $header = array_map('trim', $row);
                } else {
                    $csvData[] = array_combine($header, array_map('trim', $row));
                }
            }
            fclose($handle);
        }

        return $csvData;
    }

    private function validateCsvData($csvData)
    {
        $validatedTasks = [];
        $errors = [];
        $warnings = [];
        $createdStudents = [];

        foreach ($csvData as $index => $row) {
            $rowNumber = $index + 2;

            $student = Student::whereHas('user', function($query) use ($row) {
                $query->where('email', $row['student_email']);
            })->first();

            if (!$student) {
                $student = $this->autoCreateStudent($row['student_email'], $rowNumber, $warnings, $createdStudents);
                if (!$student) continue;
            }

            $task = Task::find($row['task_id']);
            if (!$task) {
                $errors[] = "Row {$rowNumber}: Task with ID '{$row['task_id']}' not found";
                continue;
            }

            // Check for duplicate assignment
            if ($task->students()->where('student_id', $student->id)->exists()) {
                $errors[] = "Row {$rowNumber}: Task '{$task->title}' is already assigned to '{$row['student_email']}'";
                continue;
            }

            $validator = Validator::make($row, [
                'student_email' => 'required|email',
                'task_id' => 'required|integer',
                'status' => 'required|in:assigned,in_progress,submitted,graded,overdue',
                'score' => 'nullable|numeric|min:0',
                'xp_earned' => 'nullable|integer|min:0',
                'submitted_at' => 'nullable|date',
                'graded_at' => 'nullable|date'
            ]);

            if ($validator->fails()) {
                $errors[] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            $validatedTasks[] = [
                'task_id' => $task->id,
                'student_id' => $student->id,
                'pivot_data' => [
                    'status' => $row['status'],
                    'score' => !empty($row['score']) ? (float) $row['score'] : null,
                    'xp_earned' => !empty($row['xp_earned']) ? (int) $row['xp_earned'] : 0,
                    'submitted_at' => !empty($row['submitted_at']) ? $row['submitted_at'] : null,
                    'graded_at' => !empty($row['graded_at']) ? $row['graded_at'] : null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
        }

        if (!empty($errors)) {
            throw new \Exception("CSV validation failed:\n" . implode("\n", $errors));
        }

        session(['csv_warnings' => $warnings, 'csv_created_students' => $createdStudents]);
        return $validatedTasks;
    }

    private function createTasksFromCsv($validatedTasks)
    {
        $createdCount = 0;
        
        DB::transaction(function () use ($validatedTasks, &$createdCount) {
            foreach ($validatedTasks as $taskData) {
                $task = Task::find($taskData['task_id']);
                $task->students()->attach($taskData['student_id'], $taskData['pivot_data']);
                $createdCount++;
            }
        });

        return $createdCount;
    }

    private function autoCreateStudent($email, $rowNumber, &$warnings, &$createdStudents)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->student) {
            return $existingUser->student;
        }

        try {
            DB::beginTransaction();

            if ($existingUser) {
                $student = Student::create([
                    'user_id' => $existingUser->id,
                    'enrollment_date' => now(),
                    'status' => 'active'
                ]);
            } else {
                $user = User::create([
                    'name' => $this->extractNameFromEmail($email),
                    'email' => $email,
                    'password' => bcrypt($this->generateTemporaryPassword()),
                    'email_verified_at' => null
                ]);

                $student = Student::create([
                    'user_id' => $user->id,
                    'enrollment_date' => now(),
                    'status' => 'pending'
                ]);
            }

            DB::commit();
            $warnings[] = "Row {$rowNumber}: Created student account for '{$email}'";
            $createdStudents[] = $email;
            return $student;

        } catch (\Exception $e) {
            DB::rollBack();
            $warnings[] = "Row {$rowNumber}: Failed to create student account for '{$email}': " . $e->getMessage();
            return null;
        }
    }

    private function extractNameFromEmail($email)
    {
        $localPart = explode('@', $email)[0];
        $nameParts = explode('.', $localPart);
        return ucwords(implode(' ', $nameParts));
    }

    private function generateTemporaryPassword()
    {
        return 'TempPass' . rand(1000, 9999) . '!';
    }
}