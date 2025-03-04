@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Record Absences</h1>
        <a href="{{ route('absences.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Absences</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="mb-4">
            <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Select Class</label>
            <select id="class_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select a class</option>
                @foreach($classes as $class)
                <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="students-table" class="hidden">
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="students-list">
                    <!-- Students will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const studentsTable = document.getElementById('students-table');
    const studentsList = document.getElementById('students-list');

    function loadStudents(classId) {
        if (!classId) {
            studentsTable.classList.add('hidden');
            return;
        }

        fetch(`{{ route('get.students') }}?class_id=${classId}`)
            .then(response => response.json())
            .then(students => {
                let html = '';
                students.forEach(student => {
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${student.name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${classSelect.options[classSelect.selectedIndex].text}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ url('absences/quick-record') }}/${student.id}" 
                                   class="text-indigo-600 hover:text-indigo-900">Record Absence</a>
                            </td>
                        </tr>
                    `;
                });
                studentsList.innerHTML = html;
                studentsTable.classList.remove('hidden');
            });
    }

    // Get class_id from URL if present
    const urlParams = new URLSearchParams(window.location.search);
    const preSelectedClassId = urlParams.get('class_id');
    
    if (preSelectedClassId) {
        classSelect.value = preSelectedClassId;
        loadStudents(preSelectedClassId);
    }

    classSelect.addEventListener('change', function() {
        loadStudents(this.value);
    });
});
</script>
@endpush
@endsection 