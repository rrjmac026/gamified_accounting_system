<?php

namespace App\Services;

use App\Models\XpTransaction;
use App\Models\Student;

class XpEngine
{
    public function award(
        int $studentId,
        int $amount,
        string $type,
        string $source,
        ?int $sourceId = null,
        ?string $description = null
    ) {
        // Create XP transaction record
        XpTransaction::create([
            'student_id'  => $studentId,
            'amount'      => $amount,
            'type'        => $type,
            'source'      => $source,
            'source_id'   => $sourceId,
            'description' => $description,
            'processed_at'=> now()
        ]);

        // Update total XP on student profile
        Student::find($studentId)?->increment('total_xp', $amount);
    }
}
