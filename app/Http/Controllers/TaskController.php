<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Subject;
use App\Models\Instructor;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['subject', 'instructor'])->get();
        return view('instructors.tasks.index', compact('tasks'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $instructors = Instructor::all();
        return view('instructors.tasks.create', compact('subjects', 'instructors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exercise,quiz,project',
            'subject_id' => 'required|exists:subjects,id',
            'instructor_id' => 'required|exists:instructors,id',
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

        Task::create($validated);
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully');
    }

    public function show(Task $task)
    {
        $task->load(['subject', 'instructor', 'submissions', 'questions']);
        return view('instructors.tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $subjects = Subject::all();
        $instructors = Instructor::all();
        return view('instructors.tasks.edit', compact('task', 'subjects', 'instructors'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:assignment,exercise,quiz,project',
            'subject_id' => 'required|exists:subjects,id',
            'instructor_id' => 'required|exists:instructors,id',
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
}
