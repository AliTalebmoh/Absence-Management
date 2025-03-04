<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::withCount('students')->get();
        $subjects = Subject::all();
        $totalStudents = Student::count();

        return view('dashboard.index', compact('classes', 'subjects', 'totalStudents'));
    }
}
