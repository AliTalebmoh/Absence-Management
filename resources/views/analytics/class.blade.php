@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ $analytics['class']['name'] }} Analytics</h1>
            <p class="mt-2 text-gray-600">Total Students: {{ $analytics['class']['total_students'] }}</p>
        </div>
        <a href="{{ route('analytics.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Analytics</a>
    </div>

    <!-- Today's Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Today's Attendance</h3>
            <div class="flex justify-between items-end">
                <div>
                    <p class="text-3xl font-bold text-indigo-600">
                        {{ $analytics['class']['total_students'] - $analytics['class']['today_absences'] - $analytics['class']['today_justified_absences'] }}
                    </p>
                    <p class="text-sm text-gray-500">Present</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-red-600">{{ $analytics['class']['today_absences'] }}</p>
                    <p class="text-sm text-gray-500">Absent</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-yellow-600">{{ $analytics['class']['today_justified_absences'] ?? 0 }}</p>
                    <p class="text-sm text-gray-500">Justified</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Absences</h3>
            <p class="text-3xl font-bold text-red-600">{{ $analytics['class']['total_absences'] }}</p>
            <p class="text-sm text-gray-500">Unjustified (All Time)</p>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-2">Total Justified</h3>
            <p class="text-3xl font-bold text-yellow-600">{{ $analytics['class']['total_justified_absences'] ?? 0 }}</p>
            <p class="text-sm text-gray-500">Justified (All Time)</p>
        </div>
    </div>

    <!-- Monthly Trends Chart -->
    <div class="bg-white shadow rounded-lg p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Monthly Trends</h2>
        <canvas id="monthlyTrendsChart" height="100"></canvas>
    </div>

    <!-- Student Statistics Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Student Statistics</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unjustified Absences</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justified Absences</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recent Absences</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unjustified Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justified Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($analytics['student_statistics'] as $student)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['total_absences'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['total_justified_absences'] ?? 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['recent_absences'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['total_hours'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $student['justified_hours'] ?? 0 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('students.analytics', $student['id']) }}" 
                               class="text-indigo-600 hover:text-indigo-900">View Analytics</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Trends Chart
    const monthlyData = @json($analytics['monthly_statistics']);
    new Chart(document.getElementById('monthlyTrendsChart'), {
        type: 'bar',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [
                {
                    label: 'Unjustified Absences',
                    data: monthlyData.map(item => item.total_absences),
                    backgroundColor: 'rgba(239, 68, 68, 0.2)',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Justified Absences',
                    data: monthlyData.map(item => item.total_justified_absences ?? 0),
                    backgroundColor: 'rgba(245, 158, 11, 0.2)',
                    borderColor: 'rgba(245, 158, 11, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Absences'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Monthly Absence Trends'
                }
            }
        }
    });
});
</script>
@endpush
@endsection 