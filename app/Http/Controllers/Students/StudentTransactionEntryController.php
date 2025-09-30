<?php

namespace App\Http\Controllers\Students;

use App\Http\Controllers\Controller;
use App\Models\Accounting\StudentTransactionEntry;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentTransactionEntryController extends Controller
{
    // Show list of transactions for the student to answer
    public function index()
    {
        $student = Auth::user();
        $transactions = Transaction::with('entries.account')->get(); // instructor templates
        $accounts = Account::all();

        return view('students.transactions.index', compact('transactions', 'accounts'));
    }

    // Show a form to answer a single transaction
    public function create(Transaction $transaction)
    {
        $accounts = Account::all();
        
        // Get the correct entries (instructor's template)
        $correctEntries = $transaction->entries()->with('account')->get();
        
        // Get student's previous answer if exists
        $student = Auth::user();
        $studentEntries = StudentTransactionEntry::where('student_id', $student->id)
            ->where('transaction_id', $transaction->id)
            ->with('account')
            ->get();
        
        return view('students.transactions.create', compact('transaction', 'accounts', 'correctEntries', 'studentEntries'));
    }

    public function store(Request $request, Transaction $transaction)
    {
        $student = Auth::user();

        $validated = $request->validate([
            'entries' => 'required|array|min:1',
            'entries.*.account_id' => 'required|exists:accounts,id',
            'entries.*.debit' => 'nullable|numeric',
            'entries.*.credit' => 'nullable|numeric',
        ]);

        // Delete existing entries for this transaction
        StudentTransactionEntry::where('student_id', $student->id)
            ->where('transaction_id', $transaction->id)
            ->delete();

        foreach ($validated['entries'] as $entry) {
            if (($entry['debit'] ?? 0) == 0 && ($entry['credit'] ?? 0) == 0) {
                continue;
            }

            StudentTransactionEntry::create([
                'student_id' => $student->id,
                'transaction_id' => $transaction->id,
                'account_id' => $entry['account_id'],
                'debit' => $entry['debit'] ?? 0,
                'credit' => $entry['credit'] ?? 0,
            ]);
        }

        // Check if answer is correct
        $isCorrect = $this->checkAnswer($student->id, $transaction->id);

        return redirect()->route('students.transactions.index')
            ->with($isCorrect ? 'success' : 'error', 
                $isCorrect ? 'Perfect! Your answer is correct!' : 'Your answer has been saved, but it doesn\'t match the correct answer. Try again!');
    }

    private function checkAnswer($studentId, $transactionId)
    {
        $correctEntries = Transaction::find($transactionId)->entries;
        $studentEntries = StudentTransactionEntry::where('student_id', $studentId)
            ->where('transaction_id', $transactionId)
            ->get();

        // Check if counts match
        if ($correctEntries->count() !== $studentEntries->count()) {
            return false;
        }

        // Compare each entry
        foreach ($correctEntries as $correct) {
            $match = $studentEntries->first(function ($student) use ($correct) {
                return $student->account_id == $correct->account_id
                    && abs($student->debit - $correct->debit) < 0.01
                    && abs($student->credit - $correct->credit) < 0.01;
            });

            if (!$match) {
                return false;
            }
        }

        return true;
    }
}
