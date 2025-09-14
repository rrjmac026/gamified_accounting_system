<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use App\Imports\QuizImport;
use Maatwebsite\Excel\Facades\Excel;

class QuizController extends Controller
{
    /**
     * List all quizzes
     */
    public function index()
    {
        $quizzes = Quiz::all();
        return view('instructors.quizzes.index', compact('quizzes'));
    }

    /**
     * Import quiz questions from CSV/Excel
     */
    public function import(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls'
        ]);

        Excel::import(new QuizImport($taskId), $request->file('file'));

        return redirect()
            ->route('instructors.quizzes.index')
            ->with('success', 'Questions uploaded successfully!');
    }

    /**
     * Submit answer for normal quizzes (MCQ, T/F, Identification)
     */
    public function submitAnswer(Request $request, Quiz $quiz)
    {
        $answer = $request->input('answer');
        $isCorrect = false;

        switch ($quiz->type) {
            case 'multiple_choice':
            case 'true_false':
                $isCorrect = ($answer === $quiz->correct_answer);
                break;

            case 'identification':
                $isCorrect = (strtolower(trim($answer)) === strtolower(trim($quiz->correct_answer)));
                break;
        }

        $score = $isCorrect ? $quiz->points : 0;

        $student = auth()->user()->student;

        StudentAnswer::updateOrCreate(
            [
                'student_id' => $student->id,
                'quiz_id' => $quiz->id,
            ],
            [
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'score' => $score,
            ]
        );

        return back()->with('status', 'Answer submitted!');
    }

    /**
     * Show quiz depending on role
     */
    public function show(Quiz $quiz)
    {
        $quiz->load('answers');
        $quizzes = Quiz::where('task_id', $quiz->task_id)->get();

        if (auth()->user()->role === 'instructor') {
            return view('instructors.quizzes.show', compact('quiz', 'quizzes'));
        }

        return view('quizzes.students.show', compact('quiz', 'quizzes'));
    }

    /**
     * Download Excel quiz file
     */
    public function download(Quiz $quiz)
    {
        if (!$quiz->quiz_file_path) {
            abort(404, 'No quiz file found.');
        }

        return response()->download(storage_path('app/' . $quiz->quiz_file_path));
    }

    /**
     * Submit solved Excel file for checking
     */
    public function submitExcel(Request $request, Quiz $quiz)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        $data = Excel::toArray([], $request->file('file'));

        // Decode instructor's answer key from DB (JSON format)
        $answerKey = json_decode($quiz->answer_key_json, true);

        if (!$answerKey) {
            return back()->with('error', 'No answer key found for this quiz.');
        }

        $student = auth()->user()->student;

        foreach ($answerKey as $cell => $correctAnswer) {
            // Parse Excel-style cell (e.g. "B5")
            [$col, $row] = sscanf($cell, "%[A-Z]%d");
            $colIndex = ord($col) - 65; // "A" = 0, "B" = 1, etc.
            $rowIndex = $row - 1;       // Excel rows are 1-based

            $studentAnswer = $data[0][$rowIndex][$colIndex] ?? null;

            $isCorrect = ($studentAnswer == $correctAnswer);
            $score = $isCorrect ? 1 : 0; // or assign per-cell points

            StudentAnswer::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'quiz_id' => $quiz->id,
                    'cell' => $cell, // you might need a "cell" column in StudentAnswer
                ],
                [
                    'answer' => $studentAnswer,
                    'is_correct' => $isCorrect,
                    'score' => $score,
                ]
            );
        }

        return back()->with('status', 'Excel answers submitted!');
    }

}
