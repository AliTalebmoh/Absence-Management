@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Class Details: {{ $class->name }}</h1>
            <div>
                <a href="{{ route('classes.edit', $class) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 mr-2">Edit Class</a>
                <a href="{{ route('classes.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Classes</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Students List -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Students</h2>
                    <span class="bg-indigo-100 text-indigo-800 py-1 px-3 rounded-full text-sm">
                        Total: {{ $class->students->count() }}
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach($class->students as $student)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span>{{ $student->name }}</span>
                        <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Absences -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Recent Absences</h2>
                </div>
                <div class="space-y-2">
                    @foreach($class->absences()->with(['student', 'subject'])->latest()->take(10)->get() as $absence)
                    <div class="p-3 bg-gray-50 rounded">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="font-medium">{{ $absence->student->name }}</span>
                                <span class="text-gray-500"> - {{ $absence->subject->name }}</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $absence->date->format('M d, Y') }} ({{ $absence->hours_absent }} hours)
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 