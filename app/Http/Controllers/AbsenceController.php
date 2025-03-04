<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    public function index()
    {
        $absences = Absence::with(['student', 'class', 'subject'])
            ->latest('date')
            ->paginate(15);
        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('absences.select-class', compact('classes'));
    }

    public function quickRecord(Student $student)
    {
        $subjects = Subject::all();
        return view('absences.quick-record', compact('student', 'subjects'));
    }

    public function getStudents(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)->get();
        return response()->json($students);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'period' => 'required|in:morning,afternoon,full_day',
            'hours_absent' => 'required|integer|min:1'
        ]);

        $validated['admin_id'] = Auth::id();

        Absence::create($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence recorded successfully.');
    }

    public function edit(Absence $absence)
    {
        $classes = ClassRoom::all();
        $subjects = Subject::all();
        $students = Student::where('class_id', $absence->class_id)->get();
        
        return view('absences.edit', compact('absence', 'classes', 'subjects', 'students'));
    }

    public function update(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'date' => 'required|date',
            'period' => 'required|in:morning,afternoon,full_day',
            'hours_absent' => 'required|integer|min:1'
        ]);

        $absence->update($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence updated successfully.');
    }

    public function destroy(Absence $absence)
    {
        $absence->delete();

        return redirect()->route('absences.index')
            ->with('success', 'Absence deleted successfully.');
    }
}
