@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Quick Record Absence</h1>
            <a href="{{ route('absences.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Absences</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-2">Student Information</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-gray-600">Name:</span>
                        <span class="ml-2 font-medium">{{ $student->full_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Class:</span>
                        <span class="ml-2 font-medium">{{ $student->class->name }}</span>
                    </div>
                </div>
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
                    <select name="period" id="period"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        onchange="updateHours()">
                        <option value="">Select period (optional)</option>
                        <option value="morning">Morning (4 hours)</option>
                        <option value="afternoon">Afternoon (4 hours)</option>
                        <option value="full_day">Full Day (8 hours)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="hours_absent" class="block text-sm font-medium text-gray-700 mb-2">Hours Absent</label>
                    <input type="number" name="hours_absent" id="hours_absent" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        min="0.5" max="8" step="0.5"
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
function updateHours() {
    const period = document.getElementById('period').value;
    const hoursInput = document.getElementById('hours_absent');
    
    switch(period) {
        case 'morning':
        case 'afternoon':
            hoursInput.value = '4';
            break;
        case 'full_day':
            hoursInput.value = '8';
            break;
        default:
            hoursInput.value = '1';
    }
}
</script>
@endpush
@endsection 