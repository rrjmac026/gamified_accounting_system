<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\Loggable;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use App\Models\ActivityLog;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StudentManagementController extends Controller
{
    use Loggable;

    public function index()
    {
        $students = Student::with(['user', 'subjects'])->paginate(10);
        return view('admin.student.index', compact('students'));
    }

    public function create()
    {
        
        $subjects = Subject::all();
        $courses = Course::all();
        return view('admin.student.create', compact('subjects', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'student_number' => 'required|string|unique:students,student_number',
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required|integer|min:1|max:5',
            'section' => 'required|string|max:50',
            'password' => 'required|string|min:6',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        try {
            DB::beginTransaction();

            // Create user without id_number
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student',
                'is_active' => true
            ]);

            // Create student with student_number
            $student = Student::create([
                'user_id' => $user->id,
                'student_number' => $validated['student_number'],
                'course_id' => $validated['course_id'],
                'year_level' => $validated['year_level'],
                'section' => $validated['section'],
                'total_xp' => 0,
                'current_level' => 1,
                'performance_rating' => 0.00
            ]);

            // Attach subjects if provided
            if (isset($validated['subjects'])) {
                $student->subjects()->attach($validated['subjects']);
            }

            DB::commit();

            $this->logActivity(
                "Created Student",
                "Student",
                $student->id,
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'student_number' => $student->student_number,
                    'course' => $validated['course_id']
                ]
            );

            return redirect()->route('admin.student.index')
                ->with('success', 'Student created successfully');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating student: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error creating student: ' . $e->getMessage());
        }
    }
    

    /**
     * Read CSV file natively without Laravel Excel package
     */
    private function readCsvFile($file)
    {
        $data = [];
        $path = $file->getRealPath();
        
        if (($handle = fopen($path, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $data[] = $row;
            }
            fclose($handle);
        }
        
        return $data;
    }

    /**
     * Read Excel file using Laravel Excel package
     */
    private function readExcelFile($file)
    {
        try {
            // Use the correct namespace - try different approaches
            if (class_exists('Maatwebsite\Excel\Facades\Excel')) {
                return \Maatwebsite\Excel\Facades\Excel::toArray([], $file)[0];
            } elseif (class_exists('Excel')) {
                return \Excel::toArray([], $file)[0];
            } else {
                throw new \Exception('Laravel Excel package not properly installed');
            }
        } catch (\Exception $e) {
            Log::error('Excel reading error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if Laravel Excel package is available
     */
    private function isExcelPackageAvailable()
    {
        return class_exists('Maatwebsite\Excel\Facades\Excel') || 
               class_exists('Excel') || 
               class_exists('\Maatwebsite\Excel\Excel');
    }

    public function import(Request $request) 
    {
        // Validate file based on available packages
        $allowedMimes = ['csv'];
        if ($this->isExcelPackageAvailable()) {
            $allowedMimes = ['xlsx', 'xls', 'csv'];
        }

        $request->validate([
            'file' => 'required|mimes:' . implode(',', $allowedMimes) . '|max:2048'
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();
            
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            Log::info('File upload details:', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $extension,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize()
            ]);
            
            // Handle different file types
            if (in_array(strtolower($extension), ['csv'])) {
                // Handle CSV files natively
                Log::info('Processing CSV file');
                $data = $this->readCsvFile($file);
            } elseif (in_array(strtolower($extension), ['xlsx', 'xls'])) {
                // Handle Excel files using Laravel Excel package
                Log::info('Processing Excel file');
                if ($this->isExcelPackageAvailable()) {
                    $data = $this->readExcelFile($file);
                } else {
                    throw new \Exception('Laravel Excel package is required for Excel files. Please install it with: composer require maatwebsite/excel OR convert your Excel file to CSV format.');
                }
            } else {
                throw new \Exception('Unsupported file type. Please upload a CSV' . ($this->isExcelPackageAvailable() ? ' or Excel' : '') . ' file.');
            }

            // Debug: Log the raw data structure
            Log::info('Import data structure:', [
                'total_rows' => count($data),
                'first_row' => isset($data[0]) ? $data[0] : 'No data',
                'second_row' => isset($data[1]) ? $data[1] : 'No second row'
            ]);
            
            if (empty($data)) {
                throw new \Exception('No data found in the uploaded file.');
            }
            
            $processedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                // Skip header row (row 0)
                if ($index === 0) {
                    Log::info('Header row:', ['header' => $row]);
                    continue;
                }

                // Debug: Log each row being processed
                Log::info("Processing row {$index}:", ['row' => $row]);

                // Handle empty rows
                if (empty(array_filter($row))) {
                    Log::info("Skipping empty row {$index}");
                    $skippedCount++;
                    continue;
                }

                // Extract data with better error handling - matching your CSV column order
                // CSV columns: name, email, course, year_level, section, password, id_number
                $name       = isset($row[0]) ? trim($row[0]) : null;
                $email      = isset($row[1]) ? trim($row[1]) : null;
                $course     = isset($row[2]) ? trim($row[2]) : '';
                $year_level = isset($row[3]) ? (int)$row[3] : 1;
                $section    = isset($row[4]) ? trim($row[4]) : '';
                $password   = isset($row[5]) ? trim($row[5]) : 'password123';
                $id_number  = isset($row[6]) ? trim($row[6]) : null;

                // Validate required fields
                if (empty($name) || empty($email)) {
                    $errors[] = "Row {$index}: Missing required fields (name or email)";
                    Log::warning("Row {$index}: Missing required fields", [
                        'name' => $name,
                        'email' => $email
                    ]);
                    $skippedCount++;
                    continue;
                }

                // Validate email format
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = "Row {$index}: Invalid email format ({$email})";
                    Log::warning("Row {$index}: Invalid email format", ['email' => $email]);
                    $skippedCount++;
                    continue;
                }

                try {
                    // Use provided id_number or generate one if empty
                    if (empty($id_number)) {
                        $id_number = 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    }
                    
                    // Create or update user
                    $user = User::updateOrCreate(
                        ['email' => $email],
                        [
                            'id_number' => $id_number,
                            'name' => $name,
                            'password' => Hash::make($password),
                            'role' => 'student',
                            'is_active' => true
                        ]
                    );

                    Log::info("User created/updated:", [
                        'id' => $user->id,
                        'email' => $user->email,
                        'name' => $user->name
                    ]);

                    // Create or update student
                    $student = Student::updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'course' => $course,
                            'year_level' => $year_level,
                            'section' => $section
                        ]
                    );

                    Log::info("Student created/updated:", [
                        'id' => $student->id,
                        'user_id' => $student->user_id,
                        'course' => $student->course,
                        'year_level' => $student->year_level,
                        'section' => $student->section
                    ]);

                    $processedCount++;

                } catch (\Exception $e) {
                    $errors[] = "Row {$index}: Database error - " . $e->getMessage();
                    Log::error("Error processing row {$index}:", [
                        'error' => $e->getMessage(),
                        'data' => compact('name', 'email', 'course', 'year_level', 'section')
                    ]);
                    $skippedCount++;
                }
            }

            // Commit transaction if we have at least one successful import
            if ($processedCount > 0) {
                DB::commit();
                
                $message = "Import completed! Processed: {$processedCount}, Skipped: {$skippedCount}";
                if (!empty($errors)) {
                    $message .= " | Errors: " . implode(', ', array_slice($errors, 0, 3));
                    if (count($errors) > 3) {
                        $message .= " and " . (count($errors) - 3) . " more...";
                    }
                }
                
                return back()->with('success', $message);
            } else {
                DB::rollback();
                return back()->with('error', 'No students were imported. Errors: ' . implode(', ', $errors));
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Import failed:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Error importing students: ' . $e->getMessage());
        }
    }

    public function show(Student $student)
    {
        $student->load(['user', 'subjects', 'badges', 'assignedTasks']);
        return view('admin.student.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $subjects = Subject::all();
        $courses = Course::all();
        return view('admin.student.edit', compact('student', 'subjects', 'courses'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->user_id)],
            'student_number' => ['required', 'string', Rule::unique('students')->ignore($student->id)],
            'course_id' => 'required|exists:courses,id',
            'year_level' => 'required|integer|min:1|max:5',
            'section' => 'required|string|max:50',
            'subjects' => 'array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        try {
            DB::beginTransaction();

            $student->user->update([
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);

            $student->update([
                'student_number' => $validated['student_number'],
                'course_id' => $validated['course_id'],
                'year_level' => $validated['year_level'],
                'section' => $validated['section']
            ]);

            if (isset($validated['subjects'])) {
                $student->subjects()->sync($validated['subjects']);
            }

            DB::commit();

            $this->logActivity(
                "Updated Student",
                "Student",
                $student->id,
                [
                    'student_id' => $student->id,
                    'changes' => $student->getChanges()
                ]
            );

            return redirect()->route('admin.student.show', $student)
                ->with('success', 'Student updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating student: ' . $e->getMessage());
        }
    }

    private function autoCreateStudent($email, $rowNumber, &$warnings, &$createdStudents)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $this->extractNameFromEmail($email),
                'email' => $email,
                'password' => bcrypt($this->generateTemporaryPassword()),
                'role' => 'student',
                'email_verified_at' => null
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'student_number' => 'STU' . date('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'course_id' => null, // This should be set later
                'year_level' => 1,
                'section' => null,
                'total_xp' => 0,
                'current_level' => 1,
                'performance_rating' => 0.00
            ]);

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

    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();

            // Store user ID before deleting student
            $userId = $student->user_id;

            // Delete the student record first (this will cascade delete related records)
            $student->delete();

            // Delete the associated user record
            User::where('id', $userId)->delete();

            DB::commit();

            $this->logActivity($userId, "Deleted student with user_id {$userId}");

            return redirect()->route('admin.student.index')
                ->with('success', 'Student deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting student: ' . $e->getMessage());
            
            return redirect()->route('admin.student.index')
                ->with('error', 'Error deleting student. Please try again.');
        }
    }

    public function viewAssignments(Student $student)
    {
        $assignments = $student->assignedTasks()
            ->with(['subject'])
            ->orderBy('due_date', 'desc')
            ->paginate(10);
            
        return view('instructors.students.assignments', compact('student', 'assignments'));
    }
}