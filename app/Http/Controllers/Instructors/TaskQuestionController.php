<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\TaskQuestion;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskQuestionController extends Controller
{
    public function index()
    {
        $taskQuestions = TaskQuestion::with('task')->get();
        return view('instructors.task-questions.index', compact('taskQuestions'));
    }

    public function create(Task $task)
    {
        $tasks = Task::all();
        return view('instructors.task-questions.create', compact('tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay,calculation',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:0',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string',
        ]);

        TaskQuestion::create($validated);

        return redirect()
            ->route('instructors.task-questions.index')
            ->with('success', 'Question added successfully');
    }





    public function show(TaskQuestion $taskQuestion)
    {
        return view('instructors.task-questions.show', compact('taskQuestion'));
    }

    public function edit(TaskQuestion $taskQuestion)
    {
        $tasks = Task::all();
        return view('instructors.task-questions.edit', compact('taskQuestion', 'tasks'));
    }

    public function update(Request $request, TaskQuestion $taskQuestion)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'question_text' => 'required|string',
            'question_type' => 'required|in:multiple_choice,true_false,essay,calculation',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
            'order_index' => 'required|integer|min:0',
            'options' => 'required|array',
            'options.*' => 'required|string'
        ]);

        $taskQuestion->update($validated);
        return redirect()->route('instructors.task-questions.index')
            ->with('success', 'Task question updated successfully');
    }

    public function destroy(TaskQuestion $taskQuestion)
    {
        $taskQuestion->delete();
        return redirect()->route('instructors.task-questions.index')
            ->with('success', 'Task question deleted successfully');
    }
}
