<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\QuizScore;
use App\Http\Requests\QuizScoreRequest;
use Illuminate\Http\Request;

class QuizScoreController extends Controller
{
    public function index()
    {
        $quizScores = QuizScore::with(['student', 'task'])->latest()->paginate(10);
        return view('quiz_scores.index', compact('quizScores'));
    }

    public function create()
    {
        return view('quiz_scores.create');
    }

    public function store(QuizScoreRequest $request)
    {
        QuizScore::create($request->validated());
        return redirect()->route('quiz-scores.index')
            ->with('success', 'Quiz score created successfully.');
    }

    public function show(QuizScore $quizScore)
    {
        return view('quiz_scores.show', compact('quizScore'));
    }

    public function edit(QuizScore $quizScore)
    {
        return view('quiz_scores.edit', compact('quizScore'));
    }

    public function update(QuizScoreRequest $request, QuizScore $quizScore)
    {
        $quizScore->update($request->validated());
        return redirect()->route('quiz-scores.index')
            ->with('success', 'Quiz score updated successfully.');
    }

    public function destroy(QuizScore $quizScore)
    {
        $quizScore->delete();
        return redirect()->route('quiz-scores.index')
            ->with('success', 'Quiz score deleted successfully.');
    }
}
