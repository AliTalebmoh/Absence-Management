<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'professor_id',
        'room_id',
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'frequency'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public static function getCurrentDaySchedule($classId)
    {
        $now = Carbon::now('Africa/Casablanca');
        $dayOfWeek = $now->locale('fr')->isoFormat('dddd');
        $currentTime = $now->format('H:i:s');

        return self::where('class_id', $classId)
            ->where('day_of_week', ucfirst($dayOfWeek))
            ->whereRaw("TIME(start_time) <= ? AND TIME(end_time) >= ?", [$currentTime, $currentTime])
            ->with(['subject', 'professor'])
            ->first();
    }

    public static function getDaySchedule($classId, $date)
    {
        $dayOfWeek = Carbon::parse($date, 'Africa/Casablanca')->locale('fr')->isoFormat('dddd');

        return self::where('class_id', $classId)
            ->where('day_of_week', ucfirst($dayOfWeek))
            ->with(['subject', 'professor'])
            ->orderBy('start_time')
            ->get();
    }

    public static function getCurrentPeriod()
    {
        $hour = (int) Carbon::now('Africa/Casablanca')->format('H');
        
        if ($hour >= 8 && $hour < 13) {
            return 'morning';
        } elseif ($hour >= 13 && $hour < 18) {
            return 'afternoon';
        }
        
        return 'morning'; // default to morning
    }
} 