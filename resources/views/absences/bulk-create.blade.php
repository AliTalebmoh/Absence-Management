@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6">
    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
        <h1 class="text-2xl sm:text-3xl font-bold">Record Multiple Absences</h1>
        <a href="{{ route('absences.index') }}" class="text-indigo-600 hover:text-indigo-900 text-base sm:text-lg">Back to Absences</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-4 sm:p-6">
        <form id="bulkAbsenceForm" action="{{ route('absences.store-bulk') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                <div>
                    <label for="class_id" class="block text-base font-medium text-gray-700 mb-2">Class</label>
                    <select name="class_id" id="class_id" required
                        class="w-full px-3 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select a class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-base font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="date" id="date" required
                        class="w-full px-3 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ date('Y-m-d') }}">
                </div>

                <div>
                    <label for="defaultPeriod" class="block text-base font-medium text-gray-700 mb-2">Period</label>
                    <select id="defaultPeriod" class="w-full px-3 py-2 text-base border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="morning">Morning (4 hours)</option>
                        <option value="afternoon">Afternoon (4 hours)</option>
                    </select>
                </div>
            </div>

            <div id="studentList" class="hidden">
                <div class="overflow-x-auto -mx-4 sm:-mx-6">
                    <div class="inline-block min-w-full py-2 align-middle px-4 sm:px-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="selectAll" class="w-6 h-6 sm:w-8 sm:h-8 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                            <span class="ml-2 text-xs sm:text-base font-medium text-gray-500 uppercase tracking-wider">Select All</span>
                                        </div>
                                    </th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-sm sm:text-lg font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                                    <th class="px-4 sm:px-6 py-3 text-left text-sm sm:text-lg font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="studentTableBody">
                                <!-- Students will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex justify-end px-4 sm:px-6">
                    <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white px-6 py-3 text-base font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Record Absences
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const dateInput = document.getElementById('date');
    const selectAllCheckbox = document.getElementById('selectAll');
    const studentList = document.getElementById('studentList');
    const defaultPeriod = document.getElementById('defaultPeriod');

    function loadStudents() {
        const classId = classSelect.value;
        if (!classId) {
            studentList.classList.add('hidden');
            return;
        }

        fetch(`{{ route('get.students') }}?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                updateStudentList(data.students);
                studentList.classList.remove('hidden');
                defaultPeriod.value = data.currentPeriod;
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load students. Please try again.');
            });
    }

    function updateStudentList(students) {
        const tbody = document.getElementById('studentTableBody');
        tbody.innerHTML = '';

        students.forEach(student => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50';
            tr.innerHTML = `
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <input type="checkbox" name="absences[${student.id}][student_id]" value="${student.id}"
                            class="student-checkbox w-6 h-6 sm:w-8 sm:h-8 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        <input type="hidden" name="absences[${student.id}][period]" value="${defaultPeriod.value}" class="period-input">
                        <input type="hidden" name="absences[${student.id}][hours_absent]" value="4" class="hours-input">
                    </div>
                </td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-base sm:text-xl">${student.first_name}</td>
                <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-base sm:text-xl">${student.last_name}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    classSelect.addEventListener('change', loadStudents);
    
    defaultPeriod.addEventListener('change', function() {
        document.querySelectorAll('.period-input').forEach(input => {
            input.value = this.value;
        });
    });

    selectAllCheckbox.addEventListener('change', function() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    document.getElementById('bulkAbsenceForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const checkedStudents = document.querySelectorAll('.student-checkbox:checked');
        if (checkedStudents.length === 0) {
            alert('Please select at least one student.');
            return;
        }

        this.submit();
    });
});
</script>
@endpush
@endsection 