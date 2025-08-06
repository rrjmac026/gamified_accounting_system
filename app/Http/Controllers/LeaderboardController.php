<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\Student;
use App\Models\Subject;
use App\Http\Requests\LeaderboardRequest;

class LeaderboardController extends Controller
{
    public function index()
    {
        $leaderboards = Leaderboard::with(['student', 'subject'])->get();
        return view('leaderboards.index', compact('leaderboards'));
    }

    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('leaderboards.create', compact('students', 'subjects'));
    }

    public function store(LeaderboardRequest $request)
    {
        Leaderboard::create($request->validated());
        return redirect()->route('leaderboards.index')
            ->with('success', 'Leaderboard entry created successfully.');
    }

    public function show(Leaderboard $leaderboard)
    {
        return view('leaderboards.show', compact('leaderboard'));
    }

    public function edit(Leaderboard $leaderboard)
    {
        $students = Student::all();
        $subjects = Subject::all();
        return view('leaderboards.edit', compact('leaderboard', 'students', 'subjects'));
    }

    public function update(LeaderboardRequest $request, Leaderboard $leaderboard)
    {
        $leaderboard->update($request->validated());
        return redirect()->route('leaderboards.index')
            ->with('success', 'Leaderboard entry updated successfully.');
    }

    public function destroy(Leaderboard $leaderboard)
    {
        $leaderboard->delete();
        return redirect()->route('leaderboards.index')
            ->with('success', 'Leaderboard entry deleted successfully.');
    }
}
