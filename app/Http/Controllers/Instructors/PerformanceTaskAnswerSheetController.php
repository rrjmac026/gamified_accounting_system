<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\PerformanceTask;
use App\Models\PerformanceTaskAnswerSheet;
use Illuminate\Http\Request;

class PerformanceTaskAnswerSheetController extends Controller
{
    /**
     * Show all performance tasks with answer sheet counts
     */
    public function index()
    {
        $instructor = auth()->user()->instructor;
        
        if (!$instructor) {
            abort(403, 'Not authorized as an instructor');
        }
        
        $tasks = PerformanceTask::where('instructor_id', $instructor->id)
            ->withCount('answerSheets')
            ->latest()
            ->get();

        return view('instructors.performance-tasks.answer-sheets.index', compact('tasks'));
    }

    /**
     * Show the 10 steps for a selected performance task
     */
    public function show(PerformanceTask $task)
    {
        // Verify ownership
        $instructor = auth()->user()->instructor;
        if ($task->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized access');
        }

        $answerSheets = PerformanceTaskAnswerSheet::where('performance_task_id', $task->id)
            ->orderBy('step')
            ->get()
            ->keyBy('step'); // Key by step number for easy access

        return view('instructors.performance-tasks.answer-sheets.show', compact('task', 'answerSheets'));
    }

    /**
     * Edit or Create specific step answer sheet
     */
    public function edit(PerformanceTask $task, $step)
    {
        // Verify ownership
        $instructor = auth()->user()->instructor;
        if ($task->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized access');
        }

        // Validate step number
        if ($step < 1 || $step > 10) {
            return redirect()->route('instructors.performance-tasks.answer-sheets.show', $task)
                ->with('error', 'Invalid step number. Must be between 1 and 10.');
        }

        $sheet = PerformanceTaskAnswerSheet::firstOrNew([
            'performance_task_id' => $task->id,
            'step' => $step,
        ]);

        return view("instructors.performance-tasks.answer-sheets.step-$step", compact('task', 'sheet'));
    }

    /**
     * Update or create answer sheet for a specific step
     */
    public function update(Request $request, PerformanceTask $task, $step)
    {
        // Verify ownership
        $instructor = auth()->user()->instructor;
        if ($task->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized access');
        }

        // Validate step number
        if ($step < 1 || $step > 10) {
            return redirect()->route('instructors.performance-tasks.answer-sheets.show', $task)
                ->with('error', 'Invalid step number. Must be between 1 and 10.');
        }

        // Validate the correct_data input
        $request->validate([
            'correct_data' => 'required|string'
        ]);

        try {
            // Create or update the answer sheet
            $answerSheet = PerformanceTaskAnswerSheet::updateOrCreate(
                [
                    'performance_task_id' => $task->id,
                    'step' => $step
                ],
                [
                    'correct_data' => $request->input('correct_data')
                ]
            );

            // Determine next step
            $nextStep = $step + 1;
            
            // If we've completed all 10 steps, redirect to the overview
            if ($nextStep > 10) {
                return redirect()->route('instructors.performance-tasks.answer-sheets.show', $task)
                    ->with('success', "All 10 answer sheets have been saved successfully!");
            }

            // Otherwise, redirect to the next step
            return redirect()->route('instructors.performance-tasks.answer-sheets.edit', ['task' => $task, 'step' => $nextStep])
                ->with('success', "Answer sheet for Step {$step} saved successfully! Proceed to Step {$nextStep}.");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error saving answer sheet: ' . $e->getMessage());
        }
    }

    /**
     * Delete an answer sheet for a specific step
     */
    public function destroy(PerformanceTask $task, $step)
    {
        // Verify ownership
        $instructor = auth()->user()->instructor;
        if ($task->instructor_id !== $instructor->id) {
            abort(403, 'Unauthorized access');
        }

        $answerSheet = PerformanceTaskAnswerSheet::where('performance_task_id', $task->id)
            ->where('step', $step)
            ->first();

        if ($answerSheet) {
            $answerSheet->delete();
            return back()->with('success', "Answer sheet for Step {$step} deleted successfully.");
        }

        return back()->with('error', "Answer sheet for Step {$step} not found.");
    }
}