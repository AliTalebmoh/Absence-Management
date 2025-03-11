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
        $classes = ClassRoom::all();
        $statistics = $this->analyticsService->getOverallStatistics();
        
        return view('analytics.index', [
            'classes' => $classes,
            'statistics' => $statistics,
            'analyticsService' => $this->analyticsService
        ]);
    }

    public function show(ClassRoom $class)
    {
        $statistics = $this->analyticsService->getClassStatistics($class->id);
        return view('analytics.show', compact('class', 'statistics'));
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
