<?php

namespace App\Services;

use App\Models\ClassRoom;
use App\Models\Absence;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    public function getOverallStatistics()
    {
        $totalStudents = Student::count();
        $totalAbsences = Absence::whereDate('date', Carbon::today())->count();

        return [
            'total_classes' => ClassRoom::count(),
            'total_students' => $totalStudents,
            'total_present' => $totalStudents - $totalAbsences,
            'total_absences' => $totalAbsences,
            'attendance_rate' => $this->calculateAttendanceRate($totalStudents, $totalAbsences),
            'monthly_trends' => $this->getMonthlyTrends()
        ];
    }

    public function getClassStatistics($classId)
    {
        // Get total students in class
        $totalStudents = Student::where('class_id', $classId)->count();
        
        // Get today's absences for this class
        $todayAbsences = Absence::whereHas('student', function($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->whereDate('date', Carbon::today())
            ->count();
        
        // Calculate today's attendance
        $todayPresent = $totalStudents - $todayAbsences;
        
        // Get monthly attendance data
        $monthlyAttendance = $this->getMonthlyAttendanceForClass($classId);
        
        return [
            'total_students' => $totalStudents,
            'total_present' => $todayPresent,
            'total_absences' => $todayAbsences,
            'attendance_rate' => $this->calculateAttendanceRate($totalStudents, $todayAbsences),
            'monthly_attendance' => $monthlyAttendance
        ];
    }

    private function calculateAttendanceRate($totalStudents, $absences)
    {
        if ($totalStudents === 0) {
            return 0;
        }

        $present = $totalStudents - $absences;
        return round(($present / $totalStudents) * 100, 1);
    }

    private function getMonthlyTrends()
    {
        $months = collect([]);
        
        // Get the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $totalStudents = Student::count();
            $absences = Absence::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
            
            $months->put($date->format('F'), [
                'absences' => $absences,
                'attendance_rate' => $this->calculateAttendanceRate($totalStudents, $absences)
            ]);
        }
        
        return $months;
    }

    private function getMonthlyAttendanceForClass($classId)
    {
        $months = collect([]);
        
        // Get the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Get absences for this month
            $absences = Absence::whereHas('student', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->count();
            
            $months->put($date->format('F'), $absences);
        }
        
        return $months->toJson();
    }
} 