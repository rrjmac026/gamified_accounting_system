<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class StudentTransactionEntry extends Model
{
    protected $fillable = [
        'student_id',           // Which student submitted this
        'transaction_id',       // The instructor's transaction
        'account_id',           // Which account this entry affects
        'debit',
        'credit',
        'status'               // Optional: correct/incorrect, or null before grading
    ];

    // Link to the student (user)
    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    // Link to the instructor's transaction (the template)
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    // Link to the account
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
