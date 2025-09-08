<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\TaskAnswer;
use Illuminate\Http\Request;

class TaskAnswerController extends Controller
{
    /**
     * List all task answers (for admin/instructor audit).
     */
    public function index()
    {
        $taskAnswers = TaskAnswer::with(['submission.student.user', 'question'])->paginate(15);
        return view('task-answers.index', compact('taskAnswers'));
    }

    /**
     * Show a single task answer.
     */
    public function show(TaskAnswer $taskAnswer)
    {
        $taskAnswer->load(['submission.student.user', 'question']);
        return view('task-answers.show', compact('taskAnswer'));
    }

    /**
     * Edit correctness/points for grading.
     */
    public function edit(TaskAnswer $taskAnswer)
    {
        return view('task-answers.edit', compact('taskAnswer'));
    }

    /**
     * Update grading (is_correct, points_earned).
     */
    public function update(Request $request, TaskAnswer $taskAnswer)
    {
        $validated = $request->validate([
            'is_correct' => 'required|boolean',
            'points_earned' => 'required|numeric|min:0'
        ]);

        $taskAnswer->update($validated);

        return redirect()
            ->route('task-answers.index')
            ->with('success', 'Task answer updated successfully (graded)');
    }

    /**
     * ❌ No manual create/store/destroy.
     * TaskAnswers are created automatically during TaskSubmission.
     */
}
