<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; 

use App\Models\XpTransaction;
use App\Models\Student;
use Illuminate\Http\Request;

class XpTransactionController extends Controller
{
    public function index()
    {
        $transactions = XpTransaction::with('student')->latest('processed_at')->get();
        return view('admin.xp-transactions.index', compact('transactions'));
    }

    public function create()
    {
        $students = Student::all();
        $types = ['earned', 'bonus', 'penalty', 'adjustment'];
        $sources = ['task_completion', 'quiz_score', 'bonus_activity', 'manual'];
        return view('admin.xp-transactions.create', compact('students', 'types', 'sources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|integer',
            'type' => 'required|in:earned,bonus,penalty,adjustment',
            'source' => 'required|in:task_completion,quiz_score,bonus_activity,manual',
            'source_id' => 'nullable|string',
            'description' => 'required|string',
            'processed_at' => 'required|date'
        ]);

        XpTransaction::create($validated);
        return redirect()->route('admin.xp-transactions.index')
            ->with('success', 'XP Transaction created successfully');
    }

    public function show(XpTransaction $xpTransaction)
    {
        return view('admin.xp-transactions.show', compact('xpTransaction'));
    }

    public function edit(XpTransaction $xpTransaction)
    {
        $students = Student::all();
        $types = ['earned', 'bonus', 'penalty', 'adjustment'];
        $sources = ['task_completion', 'quiz_score', 'bonus_activity', 'manual'];
        return view('admin.xp-transactions.edit', compact('xpTransaction', 'students', 'types', 'sources'));
    }

    public function update(Request $request, XpTransaction $xpTransaction)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount' => 'required|integer',
            'type' => 'required|in:earned,bonus,penalty,adjustment',
            'source' => 'required|in:task_completion,quiz_score,bonus_activity,manual',
            'source_id' => 'nullable|string',
            'description' => 'required|string',
            'processed_at' => 'required|date'
        ]);

        $xpTransaction->update($validated);
        return redirect()->route('admin.xp-transactions.index')
            ->with('success', 'XP Transaction updated successfully');
    }

    public function destroy(XpTransaction $xpTransaction)
    {
        $xpTransaction->delete();
        return redirect()->route('admin.xp-transactions.index')
            ->with('success', 'XP Transaction deleted successfully');
    }
}
