<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'name',        // Account name, e.g., "Cash", "Service Revenue"
        'code',        // Optional: Account code/number
        'type',        // Asset, Liability, Equity, Revenue, Expense
        'description', // Optional description
        'normal_balance' // 'debit' or 'credit'
    ];

    /**
     * Relationship: an account can have many transaction entries
     */
    public function instructorEntries()
    {
        return $this->hasMany(InstructorTransactionEntry::class);
    }

    public function studentEntries()
    {
        return $this->hasMany(StudentTransactionEntry::class);
    }
    /**
     * Optional: Get current balance
     */
    public function balance()
    {
        // Sum all debits - credits for this account
        return $this->transactionEntries()->sum('debit') - $this->transactionEntries()->sum('credit');
    }
}
