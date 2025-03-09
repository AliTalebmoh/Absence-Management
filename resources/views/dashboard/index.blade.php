@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Students Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Students</h2>
            <p class="text-3xl font-bold text-indigo-600">{{ $totalStudents }}</p>
        </div>

        <!-- Total Classes Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-2">Total Classes</h2>
            <p class="text-3xl font-bold text-indigo-600">{{ $classes->count() }}</p>
        </div>
    </div>

    <!-- Classes Overview -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Classes Overview</h2>
        <div class="space-y-4">
            @foreach($classes as $class)
            <div class="flex justify-between items-center">
                <span class="font-medium">{{ $class->name }}</span>
                <span class="bg-indigo-100 text-indigo-800 py-1 px-3 rounded-full text-sm">
                    {{ $class->students_count }} students
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 