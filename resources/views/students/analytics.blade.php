@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Absence Analytics: {{ $student->name }}</h1>
            <div>
                <a href="{{ route('students.show', $student) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-md hover:bg-indigo-600 mr-2">View Details</a>
                <a href="{{ route('students.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Students</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Subject-wise Absence Analysis -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Absence by Subject</h2>
                <div class="space-y-4">
                    @foreach($absences as $absence)
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium">{{ $absence->subject_name }}</span>
                            <span class="text-red-600 font-medium">{{ $absence->total_hours }} hours</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Total Absences: {{ $absence->absence_count }}</span>
                            <span>Average: {{ number_format($absence->total_hours / $absence->absence_count, 1) }} hours/absence</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ min(($absence->total_hours / max(array_column($absences->toArray(), 'total_hours')) * 100), 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Monthly Trend -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Monthly Absence Trend</h2>
                <div class="space-y-3">
                    @foreach($monthlyAbsences as $absence)
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">{{ date('F Y', mktime(0, 0, 0, $absence->month, 1, $absence->year)) }}</span>
                            <span class="text-red-600">{{ $absence->total_hours }} hours</span>
                        </div>
                        <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-600 h-2 rounded-full" style="width: {{ min(($absence->total_hours / max(array_column($monthlyAbsences->toArray(), 'total_hours')) * 100), 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Summary Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-600 mb-1">Total Absence Hours</div>
                    <div class="text-2xl font-bold text-red-600">
                        {{ $absences->sum('total_hours') }}
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-600 mb-1">Most Missed Subject</div>
                    <div class="text-2xl font-bold text-indigo-600">
                        {{ $absences->sortByDesc('total_hours')->first()->subject_name ?? 'N/A' }}
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <div class="text-gray-600 mb-1">Average Hours/Month</div>
                    <div class="text-2xl font-bold text-yellow-600">
                        {{ number_format($monthlyAbsences->avg('total_hours'), 1) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 