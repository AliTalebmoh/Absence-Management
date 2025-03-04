@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Edit Absence Record</h1>
            <a href="{{ route('absences.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Absences</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('absences.update', $absence) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <select name="class_id" id="class_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $absence->class_id) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">Student</label>
                    <select name="student_id" id="student_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a student</option>
                        @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ old('student_id', $absence->student_id) == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select name="subject_id" id="subject_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a subject</option>
                        @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $absence->subject_id) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" id="date" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('date', $absence->date->format('Y-m-d')) }}">
                </div>

                <div class="mb-4">
                    <label for="period" class="block text-sm font-medium text-gray-700 mb-2">Period</label>
                    <select name="period" id="period" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a period</option>
                        <option value="morning" {{ old('period', $absence->period) == 'morning' ? 'selected' : '' }}>Morning</option>
                        <option value="afternoon" {{ old('period', $absence->period) == 'afternoon' ? 'selected' : '' }}>Afternoon</option>
                        <option value="full_day" {{ old('period', $absence->period) == 'full_day' ? 'selected' : '' }}>Full Day</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="hours_absent" class="block text-sm font-medium text-gray-700 mb-2">Hours Absent</label>
                    <input type="number" name="hours_absent" id="hours_absent" required min="1"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('hours_absent', $absence->hours_absent) }}">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Update Absence
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');
    const periodSelect = document.getElementById('period');
    const hoursAbsentInput = document.getElementById('hours_absent');
    const currentStudentId = '{{ $absence->student_id }}';

    // Handle period changes
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

    // Handle class changes
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        if (!classId) {
            studentSelect.innerHTML = '<option value="">Select a student</option>';
            return;
        }

        fetch(`{{ route('get.students') }}?class_id=${classId}`)
            .then(response => response.json())
            .then(students => {
                let options = '<option value="">Select a student</option>';
                students.forEach(student => {
                    const selected = student.id == currentStudentId ? 'selected' : '';
                    options += `<option value="${student.id}" ${selected}>${student.name}</option>`;
                });
                studentSelect.innerHTML = options;
            });
    });
});
</script>
@endpush
@endsection 