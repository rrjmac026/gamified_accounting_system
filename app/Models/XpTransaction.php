<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XpTransaction extends Model
{
    

    protected $fillable = [
        'student_id',
        'amount',
        'type', // earned, bonus, penalty, adjustment
        'source', // task_completion, quiz_score, bonus_activity, manual
        'source_id',
        'description',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'integer',
        'processed_at' => 'datetime'
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
