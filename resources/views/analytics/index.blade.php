@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header with Stats -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Analytics Dashboard</h1>
                <p class="text-gray-600 mt-1">Overview of all classes attendance and performance</p>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-6">
            <!-- Total Classes Card -->
            <div class="bg-white rounded-2xl p-6 relative overflow-hidden border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Classes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total_classes'] }}</p>
                    </div>
                    <div class="bg-blue-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                </div>
                <div class="absolute left-0 top-0 w-1.5 h-full bg-blue-500"></div>
            </div>

            <!-- Total Students Card -->
            <div class="bg-white rounded-2xl p-6 relative overflow-hidden border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total_students'] }}</p>
                    </div>
                    <div class="bg-green-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute left-0 top-0 w-1.5 h-full bg-green-500"></div>
            </div>

            <!-- Total Present Card -->
            <div class="bg-white rounded-2xl p-6 relative overflow-hidden border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Present</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total_present'] }}</p>
                    </div>
                    <div class="bg-indigo-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute left-0 top-0 w-1.5 h-full bg-indigo-500"></div>
            </div>

            <!-- Total Absences Card -->
            <div class="bg-white rounded-2xl p-6 relative overflow-hidden border border-gray-100 shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Absences</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $statistics['total_absences'] }}</p>
                    </div>
                    <div class="bg-red-50 rounded-full p-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="absolute left-0 top-0 w-1.5 h-full bg-red-500"></div>
            </div>
        </div>
    </div>

    <!-- Classes Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Classes Overview</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Class Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Total Students</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Attendance Rate</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Present</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Absences</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($classes as $class)
                    @php
                        $classStats = $analyticsService->getClassStatistics($class->id);
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $classStats['total_students'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="flex-grow w-24 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 rounded-full {{ $classStats['attendance_rate'] >= 75 ? 'bg-emerald-500' : ($classStats['attendance_rate'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                         style="width: {{ $classStats['attendance_rate'] }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-medium {{ 
                                    $classStats['attendance_rate'] >= 75 ? 'text-emerald-600' : 
                                    ($classStats['attendance_rate'] >= 50 ? 'text-amber-600' : 'text-rose-600') 
                                }}">
                                    {{ number_format($classStats['attendance_rate'], 1) }}%
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $classStats['total_present'] }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $classStats['total_absences'] }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('analytics.show', $class->id) }}" 
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection 