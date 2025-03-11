<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'total_students',
        'total_absences',
        'total_present',
        'attendance_rate',
        'average_performance',
        'monthly_attendance',
        'subject_performance'
    ];

    protected $casts = [
        'monthly_attendance' => 'array',
        'subject_performance' => 'array'
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }
}
