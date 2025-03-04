@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Record New Absence</h1>
            <a href="{{ route('absences.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Absences</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('absences.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <select name="class_id" id="class_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                    </select>
                </div>

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
    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');

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
                    options += `<option value="${student.id}">${student.name}</option>`;
                });
                studentSelect.innerHTML = options;
            });
    });
});
</script>
@endpush
@endsection 