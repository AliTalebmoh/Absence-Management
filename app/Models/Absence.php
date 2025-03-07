<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absence extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'date',
        'period',
        'hours_absent'
    ];

    protected $casts = [
        'date' => 'date',
        'hours_absent' => 'decimal:1'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
