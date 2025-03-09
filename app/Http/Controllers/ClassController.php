<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClassController extends Controller
{
    public function index()
    {
        $classes = ClassRoom::withCount('students')->get();
        return view('classes.index', compact('classes'));
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name'
        ]);

        ClassRoom::create($validated);

        return redirect()->route('classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function show(ClassRoom $class)
    {
        $class->load('students');
        return view('classes.show', compact('class'));
    }

    public function edit(ClassRoom $class)
    {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, ClassRoom $class)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:classes,name,' . $class->id
        ]);

        $class->update($validated);

        // Clear the classes list cache
        Cache::forget('classes_list');
        
        // Clear student-related caches
        foreach ($class->students as $student) {
            Cache::forget('student_' . $student->id);
            Cache::forget('student_analytics_' . $student->id);
        }

        // Clear any paginated student results
        $cacheKeys = Cache::get('student_cache_keys', []);
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
        Cache::forget('student_cache_keys');

        return redirect()->route('classes.index')
            ->with('success', 'Class updated successfully.');
    }

    public function destroy(ClassRoom $class)
    {
        $class->delete();

        return redirect()->route('classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
