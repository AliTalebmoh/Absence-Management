@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Student Details</h1>
            <a href="{{ route('students.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Students</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-500">First Name</h3>
                    <p class="mt-1 text-lg">{{ $student->first_name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Last Name</h3>
                    <p class="mt-1 text-lg">{{ $student->last_name }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500">Class</h3>
                    <p class="mt-1 text-lg">{{ $student->class->name }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                <div class="bg-red-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-red-800">Unjustified Absences</h3>
                    <p class="text-2xl font-bold text-red-700">{{ $absences->where('justified', false)->count() }} times</p>
                    <p class="text-md text-red-600">{{ $absences->where('justified', false)->sum('hours_absent') }} hours</p>
                </div>
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <h3 class="text-lg font-medium text-yellow-800">Justified Absences</h3>
                    <p class="text-2xl font-bold text-yellow-700">{{ $absences->where('justified', true)->count() }} times</p>
                    <p class="text-md text-yellow-600">{{ $absences->where('justified', true)->sum('hours_absent') }} hours</p>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Recent Absences</h2>
                    <a href="{{ route('students.analytics', $student) }}" class="text-indigo-600 hover:text-indigo-900">Full Analytics</a>
                </div>
                @if($absences->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Justified</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($absences as $absence)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $absence->date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($absence->period) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $absence->hours_absent }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($absence->justified)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('absences.edit', $absence) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                                    <form action="{{ route('absences.destroy', $absence) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500">No absences recorded.</p>
                @endif
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('students.edit', $student) }}" 
                    class="bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700">
                    Edit Student
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 