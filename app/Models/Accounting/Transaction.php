<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['date', 'description'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function entries()
    {
        return $this->hasMany(InstructorTransactionEntry::class);
    }
}
