<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenceController extends Controller
{
    public function index()
    {
        $absences = Absence::with('student')
            ->orderBy('date', 'desc')
            ->orderBy('period', 'desc')
            ->paginate(15);
        return view('absences.index', compact('absences'));
    }

    public function create()
    {
        $classes = ClassRoom::all();
        return view('absences.create', compact('classes'));
    }

    public function bulkCreate()
    {
        $classes = ClassRoom::all();
        return view('absences.bulk-create', compact('classes'));
    }

    public function getStudents(Request $request)
    {
        $students = Student::where('class_id', $request->class_id)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name
                ];
            });
        
        return response()->json([
            'students' => $students
        ]);
    }

    public function storeBulk(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        // Filter out unchecked students
        $absences = collect($request->input('absences', []))
            ->filter(function ($absence) {
                return isset($absence['student_id']);
            });

        if ($absences->isEmpty()) {
            return redirect()->back()->with('error', 'Please select at least one student.');
        }

        foreach ($absences as $absence) {
            Absence::create([
                'student_id' => $absence['student_id'],
                'date' => $validated['date'],
                'period' => $absence['period'] ?? 'morning',
                'hours_absent' => $absence['hours_absent'] ?? 4
            ]);
        }

        return redirect()->route('absences.index')
            ->with('success', 'Absences recorded successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'period' => 'required|in:morning,afternoon',
            'hours_absent' => 'required|numeric|min:0.5|max:8'
        ]);

        Absence::create($validated);

        return redirect()->route('absences.index')
            ->with('success', 'Absence recorded successfully.');
    }

    public function edit(Absence $absence)
    {
        return view('absences.edit', compact('absence'));
    }

    public function update(Request $request, Absence $absence)
    {
        $validated = $request->validate([
            'period' => 'required|in:morning,afternoon',
            'hours_absent' => 'required|numeric|min:0.5|max:8'
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

    private function getCurrentPeriod()
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
