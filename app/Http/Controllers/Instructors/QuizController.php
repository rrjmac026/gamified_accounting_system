<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\StudentAnswer;
use App\Services\CsvTemplateService;
use Illuminate\Http\Request;
use App\Imports\QuizImport;
use Maatwebsite\Excel\Facades\Excel;

class QuizController extends Controller
{
    protected $csvTemplateService;

    public function __construct(CsvTemplateService $csvTemplateService)
    {
        $this->csvTemplateService = $csvTemplateService;
    }

    /**
     * List all quizzes
     */
    public function index()
    {
        $quizzes = Quiz::paginate(10);
        return view('instructors.quizzes.index', compact('quizzes'));
    }

    /**
     * Import quiz questions from CSV/Excel
     */
    public function import(Request $request, $taskId)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xlsx,xls',
            'quiz_id' => 'required|exists:quizzes,id'
        ]);

        $quiz = Quiz::findOrFail($request->quiz_id);
        $filePath = $request->file('file')->getPathname();

        // Validate CSV structure against template
        $validationErrors = $this->csvTemplateService->validateCsvStructure($filePath, $quiz);
        
        if (!empty($validationErrors)) {
            return redirect()->back()
                ->withErrors(['file' => $validationErrors])
                ->withInput();
        }

        Excel::import(new QuizImport($taskId), $request->file('file'));

        return redirect()
            ->route('instructors.quizzes.index')
            ->with('success', 'Questions uploaded successfully!');
    }

    /**
     * Download CSV template
     */
    public function downloadTemplate(Quiz $quiz)
    {
        return $this->csvTemplateService->downloadTemplate($quiz);
    }

    /**
     * Preview template structure via AJAX
     */
    public function previewTemplate(Request $request)
    {
        $headers = $request->input('headers', []);
        $preview = $this->csvTemplateService->getTemplatePreview($headers);
        
        return response()->json($preview);
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
        $csvTemplateService = new CsvTemplateService();
        $templateData = null;
        
        if ($quiz->csv_template_headers) {
            $templateData = $csvTemplateService->getTemplatePreview($quiz->csv_template_headers);
        }
        
        return view('instructors.quizzes.show', compact('quiz', 'templateData'));
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

    public function create()
    {
        $tasks = auth()->user()->instructor->tasks;
        $defaultHeaders = Quiz::getDefaultTemplateHeaders();
        
        return view('instructors.quizzes.create', compact('tasks', 'defaultHeaders'));
    }

    public function edit(Quiz $quiz)
    {
        $tasks = auth()->user()->instructor->tasks;
        $defaultHeaders = Quiz::getDefaultTemplateHeaders();
        
        return view('instructors.quizzes.edit', compact('quiz', 'tasks', 'defaultHeaders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,identification',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'nullable|array', // Changed from required_if to nullable
            'options.*' => 'nullable|string', // Changed validation rule
            'template_name' => 'nullable|string|max:255',
            'template_description' => 'nullable|string',
            'csv_template_headers' => 'nullable|array',
            'csv_template_headers.*' => 'nullable|string|max:255'
        ]);

        // Handle options for multiple choice questions
        if ($validated['type'] === 'multiple_choice') {
            // Filter out empty options
            $validated['options'] = array_values(array_filter($request->options));
            
            // Validate minimum options requirement
            if (count($validated['options']) < 2) {
                return back()
                    ->withErrors(['options' => 'Multiple choice questions must have at least 2 options'])
                    ->withInput();
            }
        } else {
            $validated['options'] = null;
        }

        // Clean up csv_template_headers
        if (!empty($validated['csv_template_headers'])) {
            $validated['csv_template_headers'] = array_values(array_filter(
                array_map('trim', $validated['csv_template_headers']),
                function($value) { return $value !== ''; }
            ));
        } else {
            $validated['csv_template_headers'] = null;
        }

        // Create quiz with validated data
        $quiz = Quiz::create($validated);

        return redirect()
            ->route('instructors.quizzes.index')
            ->with('success', 'Quiz question created successfully');
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,true_false,identification',
            'correct_answer' => 'required|string',
            'points' => 'required|integer|min:1',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*' => 'required_if:type,multiple_choice|string',
            'template_name' => 'nullable|string|max:255',
            'template_description' => 'nullable|string',
            'csv_template_headers' => 'nullable|array',
            'csv_template_headers.*' => 'nullable|string|max:255'
        ]);

        // Clean up and handle empty headers array
        if (!empty($validated['csv_template_headers'])) {
            $validated['csv_template_headers'] = array_values(array_filter(
                array_map('trim', $validated['csv_template_headers']),
                function($value) { return $value !== ''; }
            ));
        } else {
            $validated['csv_template_headers'] = [];
        }

        $quiz->update($validated);

        return redirect()->route('instructors.quizzes.index')
            ->with('success', 'Quiz question updated successfully');
    }
}