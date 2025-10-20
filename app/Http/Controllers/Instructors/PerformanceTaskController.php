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


class PerformanceTaskController extends Controller
{
    /**
     * Show list of performance tasks created by this instructor
     */
    // public function index()
    // {
    //     $instructorId = Auth::user()->instructor->id;

    //     $tasks = PerformanceTask::with(['section', 'instructor', 'subject'])
    //         ->where('instructor_id', $instructorId)
    //         ->get();

    //     return view('instructors.performance-tasks.index', compact('tasks'));
    // }

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'xp_reward'    => 'required|integer|min:0',
            'max_attempts' => 'required|integer|min:1',
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
            'due_date'     => 'required|date|after:now',
            'late_until'   => 'nullable|date|after:due_date',
        ]);

        $instructor = Auth::user()->instructor;

        // 1️⃣ Create the main performance task
        $task = PerformanceTask::create([
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'xp_reward'     => $validated['xp_reward'],
            'max_attempts'  => $validated['max_attempts'],
            'subject_id'    => $validated['subject_id'],
            'section_id'    => $validated['section_id'],
            'instructor_id' => $instructor->id,
        ]);

        // 3️⃣ Notify all students in the section
        foreach ($task->section->students as $student) {
            SystemNotification::create([
                'user_id' => $student->user->id,
                'title'   => 'New Performance Task Available',
                'message' => "Your instructor has assigned a new performance task: '{$task->title}'.",
                'type'    => 'info',
                'is_read' => false,
            ]);
        }

        return redirect()->route('instructors.tasks.index')
            ->with('success', 'Performance task created successfully.');
    }


    /**
     * Show single task
     */
    public function show(PerformanceTask $task)
    {
        $task->load([
            'subject',
            'section',
            'instructor',  // Remove .user
            'students'     // Remove .user
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
        
        // Load the relationships needed for display
        $task->load(['section', 'students']);

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

        return redirect()->route('instructors.tasks.index')
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

        return redirect()->route('instructors.tasks.index')
            ->with('success', 'Performance task deleted successfully.');
    }
}