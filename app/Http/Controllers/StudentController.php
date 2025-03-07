<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Cache the classes list for 24 hours
        $classes = Cache::remember('classes_list', 60 * 60 * 24, function () {
            return ClassRoom::orderBy('name')->get();
        });

        $query = Student::query()->with('class');

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Sort
        $sortField = $request->get('sort', 'last_name');
        $sortDirection = $request->get('direction', 'asc');
        
        if ($sortField === 'first_name') {
            $query->orderBy('first_name', $sortDirection)
                  ->orderBy('last_name', 'asc');
        } else {
            $query->orderBy('last_name', $sortDirection)
                  ->orderBy('first_name', 'asc');
        }

        // Cache the paginated results for 5 minutes with a unique key based on the query parameters
        $cacheKey = 'students_' . md5(json_encode($request->all()));
        $students = Cache::remember($cacheKey, 60 * 5, function () use ($query) {
            return $query->paginate(15)->withQueryString();
        });

        return view('students.index', compact('students', 'classes'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id'
        ]);

        $student = Student::create($validated);
        Cache::forget('classes_list');
        $this->clearStudentCache($student);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        // Cache individual student data with their absences for 30 minutes
        $cacheKey = 'student_' . $student->id;
        $studentData = Cache::remember($cacheKey, 60 * 30, function () use ($student) {
            $student->load(['class']);
            $absences = $student->absences()
                ->orderBy('date', 'desc')
                ->get()
                ->unique(function ($absence) {
                    return $absence->date->format('Y-m-d') . '-' . $absence->period;
                });
            
            $totalHours = $absences->sum('hours_absent');
            
            return [
                'student' => $student,
                'absences' => $absences,
                'totalHours' => $totalHours
            ];
        });
        
        return view('students.show', $studentData);
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id'
        ]);

        $student->update($validated);
        $this->clearStudentCache($student);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->clearStudentCache($student);
        $student->delete();
        Cache::forget('classes_list');

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function analytics(Student $student)
    {
        // Cache analytics data for 1 hour
        $cacheKey = 'student_analytics_' . $student->id;
        $analyticsData = Cache::remember($cacheKey, 60 * 60, function () use ($student) {
            return $student->absences()
                ->select(
                    'absences.*',
                    DB::raw('MONTH(date) as month'),
                    DB::raw('YEAR(date) as year')
                )
                ->orderBy('date')
                ->get()
                ->unique(function ($absence) {
                    return $absence->date->format('Y-m-d') . '-' . $absence->period;
                });
        });

        return view('students.analytics', [
            'student' => $student,
            'absences' => $analyticsData
        ]);
    }

    // Add cache clearing methods for data updates
    private function clearStudentCache($student)
    {
        Cache::forget('student_' . $student->id);
        Cache::forget('student_analytics_' . $student->id);
        Cache::tags(['students_list'])->flush();
    }
}
