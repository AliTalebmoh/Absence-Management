@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Edit Absence</h1>
        <a href="{{ route('absences.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Absences</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('absences.update', $absence) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                    <p class="text-gray-900">{{ $absence->student->full_name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <p class="text-gray-900">{{ $absence->date->format('M d, Y') }}</p>
                </div>

                <div>
                    <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                    <select name="period" id="period" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="morning" {{ $absence->period == 'morning' ? 'selected' : '' }}>Morning (3 hours)</option>
                        <option value="afternoon" {{ $absence->period == 'afternoon' ? 'selected' : '' }}>Afternoon (4 hours)</option>
                    </select>
                </div>

                <div>
                    <label for="hours_absent" class="block text-sm font-medium text-gray-700 mb-2">Hours Absent</label>
                    <input type="number" name="hours_absent" id="hours_absent" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        min="0.5" max="8" step="0.5" value="{{ $absence->hours_absent }}">
                </div>
                
                <div class="md:col-span-2">
                    <div class="flex items-center">
                        <input type="checkbox" name="justified" id="justified" {{ $absence->justified ? 'checked' : '' }}
                            class="h-5 w-5 rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <label for="justified" class="ml-2 block text-sm font-medium text-gray-700">
                            Justified Absence (hours will not count toward total absences)
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Update Absence
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 