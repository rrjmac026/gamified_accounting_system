<?php

namespace App\Http\Controllers;

use App\Models\TaskAnswer;
use App\Models\TaskSubmission;
use App\Models\TaskQuestion;
use Illuminate\Http\Request;

class TaskAnswerController extends Controller
{
    public function index()
    {
        $taskAnswers = TaskAnswer::with(['submission', 'question'])->get();
        return view('task-answers.index', compact('taskAnswers'));
    }

    public function create()
    {
        $submissions = TaskSubmission::all();
        $questions = TaskQuestion::all();
        return view('task-answers.create', compact('submissions', 'questions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_submission_id' => 'required|exists:task_submissions,id',
            'task_question_id' => 'required|exists:task_questions,id',
            'answer_text' => 'required|string',
            'is_correct' => 'required|boolean',
            'points_earned' => 'required|numeric|min:0'
        ]);

        TaskAnswer::create($validated);
        return redirect()->route('task-answers.index')
            ->with('success', 'Task answer created successfully');
    }

    public function show(TaskAnswer $taskAnswer)
    {
        return view('task-answers.show', compact('taskAnswer'));
    }

    public function edit(TaskAnswer $taskAnswer)
    {
        $submissions = TaskSubmission::all();
        $questions = TaskQuestion::all();
        return view('task-answers.edit', compact('taskAnswer', 'submissions', 'questions'));
    }

    public function update(Request $request, TaskAnswer $taskAnswer)
    {
        $validated = $request->validate([
            'task_submission_id' => 'required|exists:task_submissions,id',
            'task_question_id' => 'required|exists:task_questions,id',
            'answer_text' => 'required|string',
            'is_correct' => 'required|boolean',
            'points_earned' => 'required|numeric|min:0'
        ]);

        $taskAnswer->update($validated);
        return redirect()->route('task-answers.index')
            ->with('success', 'Task answer updated successfully');
    }

    public function destroy(TaskAnswer $taskAnswer)
    {
        $taskAnswer->delete();
        return redirect()->route('task-answers.index')
            ->with('success', 'Task answer deleted successfully');
    }
}
