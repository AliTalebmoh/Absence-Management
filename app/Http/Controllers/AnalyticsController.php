<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use App\Models\Absence;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index()
    {
        $stats = $this->analyticsService->getGlobalStatistics();
        return view('analytics.index', compact('stats'));
    }

    public function showClass($id)
    {
        $analytics = $this->analyticsService->getDetailedClassAnalytics($id);
        return view('analytics.class', compact('analytics'));
    }

    public function updateAnalytics($id)
    {
        $class = ClassRoom::with(['students', 'analytics'])->findOrFail($id);
        $analytics = $class->analytics;
        
        // Update total students
        $analytics->total_students = $class->students->count();
        
        // Calculate total unjustified and justified absences
        $unjustifiedAbsences = Absence::whereHas('student', function($query) use ($id) {
            $query->where('class_id', $id);
        })
        ->where('justified', false)
        ->count();
        
        $justifiedAbsences = Absence::whereHas('student', function($query) use ($id) {
            $query->where('class_id', $id);
        })
        ->where('justified', true)
        ->count();
        
        $analytics->total_absences = $unjustifiedAbsences;
        $analytics->total_justified_absences = $justifiedAbsences;
        
        // Calculate attendance rate (only considering unjustified absences)
        $totalAttendance = $analytics->total_students * 180; // Assuming 180 school days
        if ($totalAttendance > 0) {
            $analytics->attendance_rate = (($totalAttendance - $unjustifiedAbsences) / $totalAttendance) * 100;
        }
        
        $analytics->save();
        
        return redirect()->back()->with('success', 'Analytics updated successfully');
    }
}
