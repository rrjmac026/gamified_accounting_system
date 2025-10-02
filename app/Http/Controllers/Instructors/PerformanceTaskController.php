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
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $taskId = $request->route('task');
            $task = null;

            if ($taskId) {
                if ($taskId instanceof PerformanceTask) {
                    $task = $taskId;
                } else {
                    $task = PerformanceTask::find($taskId);
                }

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

    /**
     * Show list of performance tasks created by this instructor
     */
    public function index()
    {
        $instructorId = Auth::user()->instructor->id;

        // Only fetch tasks belonging to the current instructor
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
        // Get the currently authenticated instructor
        $instructor = Auth::user()->instructor;
        
        // Get only the subjects assigned to this instructor
        $subjects = $instructor->subjects()->with('sections')->get();
        
        // Get sections where this instructor is assigned
        $sections = $instructor->sections;

        return view('instructors.performance-tasks.create', compact('subjects', 'sections'));
    }

    /**
     * Store new performance task
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request
        \Log::info('Performance Task Store Request:', $request->all());

        $validated = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'xp_reward'    => 'required|integer|min:0',
            'max_attempts' => 'required|integer|min:1',
            'template_data'=> 'required|json',
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
        ]);

        // Debug: Log validated data before modifications
        \Log::info('Validated data:', $validated);

        $validated['instructor_id'] = Auth::user()->instructor->id;
        
        // Decode template_data AFTER validation
        $templateData = json_decode($validated['template_data'], true);
        $validated['template_data'] = $templateData;

        // Debug: Log final data before creation
        \Log::info('Final data for creation:', $validated);

        // Create task
        $task = PerformanceTask::create($validated);

        // Auto-assign to section students
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

            // 🔔 Notify all students in the section
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
            'template_data'=> 'required|json',
            'subject_id'   => 'required|exists:subjects,id',
            'section_id'   => 'required|exists:sections,id',
        ]);

        $validated['template_data'] = json_decode($validated['template_data'], true);

        $task->update($validated);

        // Notify students about the update
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
        // Eager load section with students before deletion
        $task->load('section.students');
        
        // Get task title before deletion for notification
        $taskTitle = $task->title;
        $students = $task->section->students ?? collect();
        
        // Delete the task (cascade will handle pivot table entries)
        $task->delete();

        // Notify students about task deletion (only if students exist)
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

    /**
     * Sync students to a specific task
     */
    public function syncStudentsToTask(PerformanceTask $task)
    {
        // Get all students currently in the task's section
        $sectionStudentIds = $task->section->students->pluck('id')->toArray();
        
        // Get students currently assigned to this task
        $assignedStudentIds = $task->students()->pluck('student_id')->toArray();
        
        // Find students who need to be assigned (in section but not assigned to task)
        $studentsToAssign = array_diff($sectionStudentIds, $assignedStudentIds);
        
        // Find students who need to be removed (assigned to task but not in section)
        $studentsToRemove = array_diff($assignedStudentIds, $sectionStudentIds);
        
        $changes = 0;
        
        // Assign new students
        if (!empty($studentsToAssign)) {
            $attachData = [];
            foreach ($studentsToAssign as $studentId) {
                $attachData[$studentId] = [
                    'status'     => 'assigned',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            $task->students()->attach($attachData);
            $changes += count($studentsToAssign);
        }
        
        // Remove students who are no longer in the section
        if (!empty($studentsToRemove)) {
            $task->students()->detach($studentsToRemove);
            $changes += count($studentsToRemove);
        }
        
        if ($changes > 0) {
            $message = '';
            if (!empty($studentsToAssign)) {
                $message .= 'Assigned task to ' . count($studentsToAssign) . ' new students. ';
            }
            if (!empty($studentsToRemove)) {
                $message .= 'Removed ' . count($studentsToRemove) . ' students who are no longer in this section.';
            }
            
            return redirect()->back()->with('success', trim($message));
        }
        
        return redirect()->back()->with('info', 'All students are already properly assigned.');
    }

    /**
     * Sync all students to all active tasks for this instructor
     */
    public function syncAllStudentsToTasks()
    {
        $instructorId = Auth::user()->instructor->id;
        $tasks = PerformanceTask::where('instructor_id', $instructorId)
                    ->with('section.students', 'students')
                    ->get();
        
        $totalAssigned = 0;
        $totalRemoved = 0;
        
        foreach ($tasks as $task) {
            // Get all students currently in the task's section
            $sectionStudentIds = $task->section->students->pluck('id')->toArray();
            
            // Get students currently assigned to this task
            $assignedStudentIds = $task->students()->pluck('student_id')->toArray();
            
            // Find students who need to be assigned
            $studentsToAssign = array_diff($sectionStudentIds, $assignedStudentIds);
            
            // Find students who need to be removed
            $studentsToRemove = array_diff($assignedStudentIds, $sectionStudentIds);
            
            // Assign new students
            if (!empty($studentsToAssign)) {
                $attachData = [];
                foreach ($studentsToAssign as $studentId) {
                    $attachData[$studentId] = [
                        'status'     => 'assigned',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                $task->students()->attach($attachData);
                $totalAssigned += count($studentsToAssign);
            }
            
            // Remove students who are no longer in the section
            if (!empty($studentsToRemove)) {
                $task->students()->detach($studentsToRemove);
                $totalRemoved += count($studentsToRemove);
            }
        }
        
        $message = '';
        if ($totalAssigned > 0) {
            $message .= "Assigned performance tasks to {$totalAssigned} students. ";
        }
        if ($totalRemoved > 0) {
            $message .= "Removed {$totalRemoved} students from tasks they're no longer eligible for.";
        }
        
        if ($totalAssigned > 0 || $totalRemoved > 0) {
            return redirect()->route('instructors.performance-tasks.index')
                ->with('success', trim($message));
        } else {
            return redirect()->route('instructors.performance-tasks.index')
                ->with('info', 'All students are already properly assigned to their section tasks.');
        }
    }
}