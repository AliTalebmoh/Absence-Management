@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Student Details: {{ $student->name }}</h1>
            <div>
                <a href="{{ route('students.analytics', $student) }}" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 mr-2">View Analytics</a>
                <a href="{{ route('students.edit', $student) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 mr-2">Edit Student</a>
                <a href="{{ route('students.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Students</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Student Information -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Student Information</h2>
                <div class="space-y-4">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <span class="ml-2 font-medium">{{ $student->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Class:</span>
                        <span class="ml-2 font-medium">{{ $student->class->name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Total Absence Hours:</span>
                        <span class="ml-2 font-medium text-red-600">{{ $totalHours }} hours</span>
                    </div>
                </div>
            </div>

            <!-- Absence Statistics by Subject -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Absence by Subject</h2>
                <div class="space-y-3">
                    @foreach($absencesBySubject as $absence)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                        <span class="font-medium">{{ $absence->name }}</span>
                        <span class="text-red-600">{{ $absence->total_hours }} hours</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Absences -->
        <div class="mt-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Recent Absences</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($student->absences()->with('subject')->latest('date')->take(10)->get() as $absence)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $absence->date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $absence->subject->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-red-600">{{ $absence->hours_absent }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 