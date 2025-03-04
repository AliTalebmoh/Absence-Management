<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('class')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('students.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id'
        ]);

        Student::create($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['class', 'absences.subject']);
        
        // Calculate total absence hours
        $totalHours = $student->absences->sum('hours_absent');
        
        // Get absence statistics by subject
        $absencesBySubject = $student->absences()
            ->select('subjects.name', DB::raw('SUM(hours_absent) as total_hours'))
            ->join('subjects', 'absences.subject_id', '=', 'subjects.id')
            ->groupBy('subjects.id', 'subjects.name')
            ->get();

        return view('students.show', compact('student', 'totalHours', 'absencesBySubject'));
    }

    public function edit(Student $student)
    {
        $classes = ClassRoom::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id'
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    public function analytics(Student $student)
    {
        $absences = $student->absences()
            ->select(
                'subjects.name as subject_name',
                DB::raw('SUM(hours_absent) as total_hours'),
                DB::raw('COUNT(*) as absence_count')
            )
            ->join('subjects', 'absences.subject_id', '=', 'subjects.id')
            ->groupBy('subjects.id', 'subjects.name')
            ->get();

        $monthlyAbsences = $student->absences()
            ->select(
                DB::raw('MONTH(date) as month'),
                DB::raw('YEAR(date) as year'),
                DB::raw('SUM(hours_absent) as total_hours')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('students.analytics', compact('student', 'absences', 'monthlyAbsences'));
    }
}
