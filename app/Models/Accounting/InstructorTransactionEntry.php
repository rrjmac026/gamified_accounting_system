<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class InstructorTransactionEntry extends Model
{
    protected $fillable = ['transaction_id', 'account_id', 'debit', 'credit'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
