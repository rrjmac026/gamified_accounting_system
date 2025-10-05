<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PerformanceTask;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SystemNotification;

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'xp_reward'    => 'required|integer|min:0',
            'max_attempts' => 'required|integer|min:1',
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
        ]);

        $validated['instructor_id'] = Auth::user()->instructor->id;

        $task = PerformanceTask::create($validated);

        $section = Section::with('students')->findOrFail($validated['section_id']);
        $attachData = [];

        if ($section->students && $section->students->count() > 0) {
            foreach ($section->students as $student) {
                $attachData[$student->id] = [
                    'status'     => 'assigned',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $task->students()->attach($attachData);

            foreach ($section->students as $student) {
                SystemNotification::create([
                    'user_id' => $student->user->id,
                    'title'   => 'New Performance Task Assigned',
                    'message' => "A new performance task '{$task->title}' has been assigned to your section.",
                    'type'    => 'info',
                    'is_read' => false,
                ]);
            }
        }

        return redirect()->route('instructors.performance-tasks.index')
            ->with('success', 'Performance task created and assigned to students.');
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
