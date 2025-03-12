<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

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
        
        // Calculate attendance rate
        if ($analytics->total_absences + $analytics->total_present > 0) {
            $analytics->attendance_rate = ($analytics->total_present / ($analytics->total_absences + $analytics->total_present)) * 100;
        }
        
        $analytics->save();
        
        return redirect()->back()->with('success', 'Analytics updated successfully');
    }
}
