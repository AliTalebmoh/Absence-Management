@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Absence Analytics for {{ $student->full_name }}</h1>
        <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900">Back to Student</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Total Absences -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Total Absences</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-gray-600 mb-1">Total Days</div>
                    <div class="text-2xl font-bold">{{ $absences->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count() }}</div>
                </div>
                <div>
                    <div class="text-gray-600 mb-1">Total Hours</div>
                    <div class="text-2xl font-bold">{{ $absences->sum('hours_absent') }}</div>
                </div>
            </div>
        </div>

        <!-- Period Analysis -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Absence by Period</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-gray-600 mb-1">Morning</div>
                    <div class="text-2xl font-bold">{{ $absences->where('period', 'morning')->count() }}</div>
                </div>
                <div>
                    <div class="text-gray-600 mb-1">Afternoon</div>
                    <div class="text-2xl font-bold">{{ $absences->where('period', 'afternoon')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="bg-white shadow-md rounded-lg p-6 md:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Monthly Trend</h2>
            <div class="space-y-4">
                @foreach($absences->groupBy(function($absence) { 
                    return $absence->date->format('F Y'); 
                }) as $month => $monthlyAbsences)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">{{ $month }}</span>
                        <span class="text-gray-600">
                            {{ $monthlyAbsences->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count() }} 
                            days ({{ $monthlyAbsences->sum('hours_absent') }} hours)
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ ($monthlyAbsences->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count() / $absences->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count()) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 