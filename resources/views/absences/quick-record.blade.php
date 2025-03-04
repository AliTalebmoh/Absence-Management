@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Record Absence for {{ $student->name }}</h1>
            <a href="{{ route('absences.create') }}?class_id={{ $student->class_id }}" class="text-indigo-600 hover:text-indigo-900">Back</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <div class="text-gray-600">Student:</div>
                <div class="font-medium">{{ $student->name }}</div>
            </div>

            <div class="mb-4">
                <div class="text-gray-600">Class:</div>
                <div class="font-medium">{{ $student->class->name }}</div>
            </div>

            <form action="{{ route('absences.store') }}" method="POST">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input type="hidden" name="class_id" value="{{ $student->class_id }}">

                <div class="mb-4">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select name="subject_id" id="subject_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a subject</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" id="date" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('date', date('Y-m-d')) }}">
                </div>

                <div class="mb-4">
                    <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                    <select name="period" id="period" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a period</option>
                        <option value="morning" {{ old('period') == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="afternoon" {{ old('period') == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                        <option value="full_day" {{ old('period') == 'full_day' ? 'selected' : '' }}>Full Day</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="hours_absent" class="block text-sm font-medium text-gray-700 mb-2">Hours Absent</label>
                    <input type="number" name="hours_absent" id="hours_absent" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('hours_absent', 1) }}">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Record Absence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('period');
    const hoursAbsentInput = document.getElementById('hours_absent');

    periodSelect.addEventListener('change', function() {
        switch(this.value) {
            case 'morning':
                hoursAbsentInput.value = '4';
                break;
            case 'afternoon':
                hoursAbsentInput.value = '4';
                break;
            case 'full_day':
                hoursAbsentInput.value = '8';
                break;
            default:
                hoursAbsentInput.value = '1';
        }
    });
});
</script>
@endpush
@endsection 