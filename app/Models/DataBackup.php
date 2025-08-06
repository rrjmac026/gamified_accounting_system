<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'backup_name',
        'file_path',
        'backup_type', // full, incremental, differential
        'file_size',
        'status', // pending, completed, failed
        'created_by',
        'backup_date',
        'retention_until'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'backup_date' => 'datetime',
        'retention_until' => 'datetime'
    ];
}
