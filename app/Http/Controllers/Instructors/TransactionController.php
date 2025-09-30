<?php

namespace App\Http\Controllers\Instructors;

use App\Http\Controllers\Controller;
use App\Models\Accounting\Transaction;
use App\Models\Accounting\InstructorTransactionEntry;
use App\Models\Accounting\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Eager-load entries and their accounts
        $transactions = Transaction::with('entries.account')->get();
        $accounts = Account::all();

        // Precompute ending balances per account
        $balances = [];
        foreach ($accounts as $account) {
            $balances[$account->id] = $transactions->flatMap->entries
                ->where('account_id', $account->id)
                ->sum(fn($e) => $e->debit - $e->credit);
        }

        return view('instructors.transactions.index', compact('transactions', 'accounts', 'balances'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('instructors.transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transactions' => 'required|array|min:1',
            'transactions.*.date' => 'required|date',
            'transactions.*.description' => 'required|string',
            'transactions.*.entries' => 'nullable|array',
            'transactions.*.entries.*.account_id' => 'required_with:transactions.*.entries|exists:accounts,id',
            'transactions.*.entries.*.debit' => 'nullable|numeric',
            'transactions.*.entries.*.credit' => 'nullable|numeric',
        ]);

        DB::transaction(function() use ($validated) {
            foreach ($validated['transactions'] as $trx) {
                // Create the transaction
                $transaction = Transaction::create([
                    'date' => $trx['date'],
                    'description' => $trx['description'],
                ]);

                // Create entries if provided
                $entries = array_filter($trx['entries'] ?? [], fn($e) => ($e['debit'] ?? 0) != 0 || ($e['credit'] ?? 0) != 0);

                if (!empty($entries)) {
                    $transaction->entries()->createMany($entries);
                }
            }
        });

        return redirect()->route('instructors.transactions.index')->with('success', 'Transactions created successfully!');
    }
}
