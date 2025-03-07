<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'class_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($student) {
            Cache::forget('student_' . $student->id);
            Cache::forget('student_analytics_' . $student->id);
            Cache::tags(['students_list'])->flush();
        });

        static::deleted(function ($student) {
            Cache::forget('student_' . $student->id);
            Cache::forget('student_analytics_' . $student->id);
            Cache::tags(['students_list'])->flush();
            Cache::forget('classes_list');
        });
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function absences()
    {
        return $this->hasMany(Absence::class);
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
