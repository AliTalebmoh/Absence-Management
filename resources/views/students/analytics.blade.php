@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Absence Analytics for {{ $student->full_name }}</h1>
        <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900">Back to Student</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Total Absences -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Unjustified Absences</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-gray-600 mb-1">Total Days</div>
                    <div class="text-2xl font-bold">{{ $absences->where('justified', false)->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count() }}</div>
                </div>
                <div>
                    <div class="text-gray-600 mb-1">Total Hours</div>
                    <div class="text-2xl font-bold">{{ $absences->where('justified', false)->sum('hours_absent') }}</div>
                </div>
            </div>
        </div>

        <!-- Justified Absences -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Justified Absences</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <div class="text-gray-600 mb-1">Total Days</div>
                    <div class="text-2xl font-bold">{{ $absences->where('justified', true)->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count() }}</div>
                </div>
                <div>
                    <div class="text-gray-600 mb-1">Total Hours</div>
                    <div class="text-2xl font-bold">{{ $absences->where('justified', true)->sum('hours_absent') }}</div>
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
                    <div class="text-sm text-gray-500">
                        ({{ $absences->where('period', 'morning')->where('justified', true)->count() }} justified)
                    </div>
                </div>
                <div>
                    <div class="text-gray-600 mb-1">Afternoon</div>
                    <div class="text-2xl font-bold">{{ $absences->where('period', 'afternoon')->count() }}</div>
                    <div class="text-sm text-gray-500">
                        ({{ $absences->where('period', 'afternoon')->where('justified', true)->count() }} justified)
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="bg-white shadow-md rounded-lg p-6 md:col-span-3">
            <h2 class="text-xl font-semibold mb-4">Monthly Trend</h2>
            <div class="space-y-4">
                @foreach($absences->groupBy(function($absence) { 
                    return $absence->date->format('F Y'); 
                }) as $month => $monthlyAbsences)
                @php
                    $unjustified = $monthlyAbsences->where('justified', false);
                    $justified = $monthlyAbsences->where('justified', true);
                    $totalDays = $monthlyAbsences->unique(function($absence) { return $absence->date->format('Y-m-d'); })->count();
                    $totalUnjustifiedHours = $unjustified->sum('hours_absent');
                    $totalJustifiedHours = $justified->sum('hours_absent');
                @endphp
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">{{ $month }}</span>
                        <span class="text-gray-600">
                            {{ $totalDays }} days 
                            ({{ $totalUnjustifiedHours }} unjustified hours, 
                            {{ $totalJustifiedHours }} justified hours)
                        </span>
                    </div>
                    <div class="flex w-full h-4 mb-2">
                        @if($absences->count() > 0)
                            <div class="bg-red-500 h-4 rounded-l-full" 
                                style="width: {{ ($unjustified->count() / $absences->count()) * 100 }}%">
                            </div>
                            <div class="bg-yellow-500 h-4 rounded-r-full" 
                                style="width: {{ ($justified->count() / $absences->count()) * 100 }}%">
                            </div>
                        @endif
                    </div>
                    <div class="flex text-xs">
                        <div class="flex items-center mr-4">
                            <div class="w-3 h-3 bg-red-500 mr-1"></div>
                            <span>Unjustified: {{ $unjustified->count() }}</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-yellow-500 mr-1"></div>
                            <span>Justified: {{ $justified->count() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 