<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
// use Maatwebsite\Excel\Facades\Excel; // Comment out if not using Excel package
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentManagementController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'subjects'])->paginate(10);
        return view('admin.student.index', compact('students'));
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

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();
            
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            
            // Handle different file types
            if (in_array(strtolower($extension), ['csv'])) {
                // Handle CSV files natively
                $data = $this->readCsvFile($file);
            } elseif (in_array(strtolower($extension), ['xlsx', 'xls'])) {
                // Handle Excel files - requires Laravel Excel package
                if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {
                    $sheets = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
                    $data = $sheets[0];
                } else {
                    throw new \Exception('Laravel Excel package is required for Excel files. Please install it with: composer require maatwebsite/excel');
                }
            } else {
                throw new \Exception('Unsupported file type. Please upload a CSV or Excel file.');
            }

            // Debug: Log the raw data structure
            Log::info('Import data structure:', ['data' => $data]);
            
            $processedCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($data as $index => $row) {
                // Skip header row (row 0)
                if ($index === 0) {
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
                    // Generate id_number if not provided
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
        return view('instructors.student.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $subjects = Subject::all();
        return view('admin.student.edit', compact('student', 'subjects'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'course' => 'required|string|max:100',
            'year_level' => 'required|integer|min:1|max:5',
            'section' => 'required|string|max:50',
            'subjects' => 'array|exists:subjects,id'
        ]);

        $student->update($validated);

        if(isset($validated['subjects'])) {
            $student->subjects()->sync($validated['subjects']);
        }

        return redirect()->route('admin.student.show', $student)
            ->with('success', 'Student updated successfully');
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