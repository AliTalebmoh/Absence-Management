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
        
        // Count only unjustified absences for total absences
        $totalAbsences = Absence::whereDate('date', Carbon::today())
            ->where('justified', false)
            ->count();
            
        // Count justified absences separately
        $totalJustifiedAbsences = Absence::whereDate('date', Carbon::today())
            ->where('justified', true)
            ->count();

        return [
            'total_classes' => ClassRoom::count(),
            'total_students' => $totalStudents,
            'total_present' => $totalStudents - $totalAbsences - $totalJustifiedAbsences,
            'total_absences' => $totalAbsences,
            'total_justified_absences' => $totalJustifiedAbsences,
            'attendance_rate' => $this->calculateAttendanceRate($totalStudents, $totalAbsences),
            'monthly_trends' => $this->getMonthlyTrends()
        ];
    }

    public function getClassStatistics($classId)
    {
        // Get total students in class
        $totalStudents = Student::where('class_id', $classId)->count();
        
        // Get today's unjustified absences for this class
        $todayAbsences = Absence::whereHas('student', function($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->whereDate('date', Carbon::today())
            ->where('justified', false)
            ->count();
            
        // Get today's justified absences for this class
        $todayJustifiedAbsences = Absence::whereHas('student', function($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->whereDate('date', Carbon::today())
            ->where('justified', true)
            ->count();
        
        // Calculate today's attendance
        $todayPresent = $totalStudents - $todayAbsences - $todayJustifiedAbsences;
        
        // Get monthly attendance data
        $monthlyAttendance = $this->getMonthlyAttendanceForClass($classId);
        
        return [
            'total_students' => $totalStudents,
            'total_present' => $todayPresent,
            'total_absences' => $todayAbsences,
            'total_justified_absences' => $todayJustifiedAbsences,
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
            $absences = Absence::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('justified', false)
                ->count();
                
            $justifiedAbsences = Absence::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('justified', true)
                ->count();
            
            $months->put($date->format('F'), [
                'absences' => $absences,
                'justified_absences' => $justifiedAbsences,
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
            
            // Get unjustified absences for this month
            $absences = Absence::whereHas('student', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->where('justified', false)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->count();
            
            // Get justified absences for this month
            $justifiedAbsences = Absence::whereHas('student', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })
                ->where('justified', true)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->count();
                
            $months->put($date->format('F'), [
                'absences' => $absences,
                'justified_absences' => $justifiedAbsences
            ]);
        }
        
        return $months->toJson();
    }

    public function getGlobalStatistics()
    {
        $totalStudents = Student::count();
        $totalClasses = ClassRoom::count();
        
        // Get today's absences
        $today = Carbon::today();
        $todayAbsences = Absence::whereDate('date', $today)
            ->where('justified', false)
            ->count();
            
        $todayJustifiedAbsences = Absence::whereDate('date', $today)
            ->where('justified', true)
            ->count();
        
        // Get total absences for the current month
        $currentMonth = Carbon::now()->startOfMonth();
        $monthlyAbsences = Absence::whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->where('justified', false)
            ->count();
            
        $monthlyJustifiedAbsences = Absence::whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->where('justified', true)
            ->count();

        // Get total absences (all time)
        $totalAbsences = Absence::where('justified', false)->count();
        $totalJustifiedAbsences = Absence::where('justified', true)->count();

        // Get class-wise statistics
        $classStats = ClassRoom::withCount(['students', 'students as absent_today' => function($query) use ($today) {
            $query->whereHas('absences', function($q) use ($today) {
                $q->whereDate('date', $today)
                  ->where('justified', false);
            });
        }, 'students as justified_today' => function($query) use ($today) {
            $query->whereHas('absences', function($q) use ($today) {
                $q->whereDate('date', $today)
                  ->where('justified', true);
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
                'justified_today' => $class->justified_today,
                'present_today' => $class->students_count - $class->absent_today - $class->justified_today,
                'attendance_rate' => round($attendanceRate, 1)
            ];
        });

        return [
            'total_students' => $totalStudents,
            'total_classes' => $totalClasses,
            'today_absences' => $todayAbsences,
            'today_justified_absences' => $todayJustifiedAbsences,
            'monthly_absences' => $monthlyAbsences,
            'monthly_justified_absences' => $monthlyJustifiedAbsences,
            'total_absences' => $totalAbsences,
            'total_justified_absences' => $totalJustifiedAbsences,
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
            
            // Count regular absences
            $absences = Absence::whereHas('student', function($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->where('justified', false)
            ->whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->count();
            
            // Count justified absences
            $justifiedAbsences = Absence::whereHas('student', function($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->where('justified', true)
            ->whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->count();

            $totalStudents = Student::where('class_id', $classId)
                ->whereDate('created_at', '<=', $month->endOfMonth())
                ->count();

            $monthlyStats->push([
                'month' => $month->format('F Y'),
                'total_absences' => $absences,
                'total_justified_absences' => $justifiedAbsences,
                'total_students' => $totalStudents,
                'average_absences' => $totalStudents > 0 ? round($absences / $totalStudents, 1) : 0
            ]);
        }

        // Get student-wise statistics
        $studentStats = Student::where('class_id', $classId)
            ->withCount([
                'absences as total_absences' => function($query) {
                    $query->where('justified', false);
                }, 
                'absences as total_justified_absences' => function($query) {
                    $query->where('justified', true);
                },
                'absences as recent_absences' => function($query) {
                    $query->whereMonth('date', Carbon::now()->month)
                          ->where('justified', false);
                }
            ])
            ->withSum(['absences as total_hours' => function($query) {
                $query->where('justified', false);
            }], 'hours_absent')
            ->withSum(['absences as justified_hours' => function($query) {
                $query->where('justified', true);
            }], 'hours_absent')
            ->orderBy('total_absences', 'desc')  // Order by most absences first
            ->get()
            ->map(function($student) {
                return [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'total_absences' => $student->total_absences,
                    'total_justified_absences' => $student->total_justified_absences,
                    'recent_absences' => $student->recent_absences,
                    'total_hours' => round($student->total_hours ?? 0, 1),
                    'justified_hours' => round($student->justified_hours ?? 0, 1)
                ];
            });

        // Get daily attendance trend for current month
        $dailyTrend = Absence::whereHas('student', function($query) use ($classId) {
            $query->where('class_id', $classId);
        })
        ->whereMonth('date', Carbon::now()->month)
        ->whereYear('date', Carbon::now()->year)
        ->select(DB::raw('DATE(date) as date'), DB::raw('justified'), DB::raw('count(*) as total'))
        ->groupBy('date', 'justified')
        ->orderBy('date')
        ->get();

        return [
            'class' => [
                'name' => $class->name,
                'total_students' => $class->students()->count(),
                'today_absences' => $class->students()
                    ->whereHas('absences', function($query) use ($today) {
                        $query->whereDate('date', $today)
                              ->where('justified', false);
                    })->count(),
                'today_justified_absences' => $class->students()
                    ->whereHas('absences', function($query) use ($today) {
                        $query->whereDate('date', $today)
                              ->where('justified', true);
                    })->count(),
                'total_absences' => Absence::whereHas('student', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })->where('justified', false)->count(),
                'total_justified_absences' => Absence::whereHas('student', function($query) use ($classId) {
                    $query->where('class_id', $classId);
                })->where('justified', true)->count()
            ],
            'monthly_statistics' => $monthlyStats,
            'student_statistics' => $studentStats,
            'daily_trend' => $dailyTrend
        ];
    }
} 