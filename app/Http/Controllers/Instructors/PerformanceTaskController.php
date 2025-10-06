<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerformanceTask;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SystemNotification;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\PerformanceTaskStep;

class PerformanceTaskController extends Controller
{
    /**
     * Show list of performance tasks created by this instructor
     */
    public function index()
    {
        $instructorId = Auth::user()->instructor->id;

        $tasks = PerformanceTask::with(['section', 'instructor', 'subject'])
            ->where('instructor_id', $instructorId)
            ->get();

        return view('instructors.performance-tasks.index', compact('tasks'));
    }

    /**
     * Show form to create a new performance task
     */
    public function create()
    {
        $instructor = Auth::user()->instructor;
        $subjects = $instructor->subjects()->with('sections')->get();
        $sections = $instructor->sections;

        return view('instructors.performance-tasks.create', compact('subjects', 'sections'));
    }

    /**
     * Store new performance task
     */
    public function saveStep(Request $request, $step)
    {
        $user = auth()->user();

        // Get the student's active task
        $task = PerformanceTask::whereHas('section.students', function ($query) use ($user) {
            $query->where('student_id', $user->student->id);
        })
        ->latest()
        ->first();

        if (!$task) {
            return back()->with('error', 'No active performance task found.');
        }

        // Retrieve instructor's correct data for this step
        $correctStep = $task->steps()->where('step_number', $step)->first();

        if (!$correctStep) {
            return back()->with('error', "Step $step data not found in instructor template.");
        }

        $correctData = $correctStep->template_data;
        $studentData = json_decode($request->template_data, true);

        // Auto-check comparison
        $matches = 0;
        $totalCells = 0;

        foreach ($correctData as $r => $row) {
            foreach ($row as $c => $value) {
                $totalCells++;
                if (trim(strtolower($studentData[$r][$c] ?? '')) === trim(strtolower($value ?? ''))) {
                    $matches++;
                }
            }
        }

        $accuracy = $totalCells > 0 ? round(($matches / $totalCells) * 100, 2) : 0;
        $remarks = $accuracy >= 80 ? 'Perfect! Your entry is balanced.' : 'Please review your answers.';

        // Save submission with auto-check results
        $submission = PerformanceTaskSubmission::updateOrCreate(
            [
                'task_id' => $task->id,
                'student_id' => $user->student->id,
                'step' => $step,
            ],
            [
                'submission_data' => $studentData,
                'status' => $accuracy >= 80 ? 'completed' : 'in-progress',
                'score' => $accuracy,
                'remarks' => $remarks,
            ]
        );

        return redirect()->route('students.performance-tasks.step', $step + 1)
            ->with('success', "Step $step checked and saved! Accuracy: {$accuracy}%");
    }


    /**
     * Show single task
     */
    public function show(PerformanceTask $task)
    {
        $task->load([
            'subject',
            'section',
            'instructor.user',
            'students.user'
        ]);

        return view('instructors.performance-tasks.show', compact('task'));
    }

    /**
     * Edit task
     */
    public function edit(PerformanceTask $task)
    {
        $instructor = Auth::user()->instructor;
        $subjects = $instructor->subjects;
        $sections = $instructor->sections;

        return view('instructors.performance-tasks.edit', compact('task', 'subjects', 'sections'));
    }

    /**
     * Update task
     */
    public function update(Request $request, PerformanceTask $task)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'xp_reward'    => 'required|integer|min:0',
            'max_attempts' => 'required|integer|min:1',
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
        ]);

        $task->update($validated);

        foreach ($task->section->students as $student) {
            SystemNotification::create([
                'user_id' => $student->user->id,
                'title'   => 'Performance Task Updated',
                'message' => "The performance task '{$task->title}' has been updated. Check for new instructions or changes.",
                'type'    => 'warning',
                'is_read' => false,
            ]);
        }

        return redirect()->route('instructors.performance-tasks.index')
            ->with('success', 'Performance task updated successfully.');
    }

    /**
     * Delete task
     */
    public function destroy(PerformanceTask $task)
    {
        $task->load('section.students');
        $taskTitle = $task->title;
        $students = $task->section->students ?? collect();

        $task->delete();

        if ($students->isNotEmpty()) {
            foreach ($students as $student) {
                SystemNotification::create([
                    'user_id' => $student->user->id,
                    'title'   => 'Performance Task Removed',
                    'message' => "The performance task '{$taskTitle}' has been removed by your instructor.",
                    'type'    => 'info',
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->route('instructors.performance-tasks.index')
            ->with('success', 'Performance task deleted successfully.');
    }
}