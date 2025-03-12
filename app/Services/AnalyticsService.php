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

    public function getGlobalStatistics()
    {
        $totalStudents = Student::count();
        $totalClasses = ClassRoom::count();
        
        // Get today's absences
        $today = Carbon::today();
        $todayAbsences = Absence::whereDate('date', $today)->count();
        
        // Get total absences for the current month
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyAbsences = Absence::whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->count();

        // Get total absences (all time)
        $totalAbsences = Absence::count();

        // Get class-wise statistics
        $classStats = ClassRoom::withCount(['students', 'students as absent_today' => function($query) use ($today) {
            $query->whereHas('absences', function($q) use ($today) {
                $q->whereDate('date', $today);
            });
        }])
        ->get()
        ->map(function($class) {
            $attendanceRate = $class->students_count > 0 
                ? (($class->students_count - $class->absent_today) / $class->students_count) * 100 
                : 0;
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'total_students' => $class->students_count,
                'absent_today' => $class->absent_today,
                'present_today' => $class->students_count - $class->absent_today,
                'attendance_rate' => round($attendanceRate, 1)
            ];
        });

        return [
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'today_absences' => $todayAbsences,
            'monthly_absences' => $monthlyAbsences,
            'total_absences' => $totalAbsences,
            'class_statistics' => $classStats
        ];
    }

    public function getDetailedClassAnalytics($classId)
    {
        $class = ClassRoom::findOrFail($classId);
        $today = Carbon::today();
        
        // Get monthly statistics for the last 6 months
        $monthlyStats = collect();
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $absences = Absence::whereHas('student', function($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->count();

            $totalStudents = Student::where('class_id', $classId)
                ->whereDate('created_at', '<=', $month->endOfMonth())
                ->count();

            $monthlyStats->push([
                'month' => $month->format('F Y'),
                'total_absences' => $absences,
                'total_students' => $totalStudents,
                'average_absences' => $totalStudents > 0 ? round($absences / $totalStudents, 1) : 0
            ]);
        }

        // Get student-wise statistics
        $studentStats = Student::where('class_id', $classId)
            ->withCount(['absences as total_absences', 'absences as recent_absences' => function($query) {
                $query->whereMonth('date', Carbon::now()->month);
            }])
            ->withSum('absences as total_hours', 'hours_absent')
            ->orderBy('total_absences', 'desc')  // Order by most absences first
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'total_absences' => $student->total_absences,
                    'recent_absences' => $student->recent_absences,
                    'total_hours' => round($student->total_hours ?? 0, 1)
                ];
            });

        // Get daily attendance trend for current month
        $dailyTrend = Absence::whereHas('student', function($query) use ($classId) {
            $query->where('class_id', $classId);
        })
        ->whereMonth('date', Carbon::now()->month)
        ->whereYear('date', Carbon::now()->year)
        ->select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return [
            'class' => [
                'name' => $class->name,
                'total_students' => $class->students()->count(),
                'today_absences' => $class->students()
                    ->whereHas('absences', function($query) use ($today) {
                        $query->whereDate('date', $today);
                    })->count(),
                'total_absences' => Absence::whereHas('student', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })->count()
            ],
            'monthly_statistics' => $monthlyStats,
            'student_statistics' => $studentStats,
            'daily_trend' => $dailyTrend
        ];
    }
} 