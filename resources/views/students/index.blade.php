@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Students</h1>
        <a href="{{ route('students.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            Add New Student
        </a>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form action="{{ route('students.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4" id="searchForm">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" name="search" id="search" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search by name..."
                        value="{{ request('search') }}">
                    @if(request('search'))
                        <button type="button" onclick="clearSearch()" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Class</label>
                <select name="class_id" id="class_id" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    onchange="document.getElementById('searchForm').submit()">
                    <option value="">All Classes</option>
                    @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort by</label>
                <select name="sort" id="sort" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    onchange="document.getElementById('searchForm').submit()">
                    <option value="last_name" {{ request('sort', 'last_name') == 'last_name' ? 'selected' : '' }}>Last Name</option>
                    <option value="first_name" {{ request('sort') == 'first_name' ? 'selected' : '' }}>First Name</option>
                </select>
                <input type="hidden" name="direction" value="{{ request('direction', 'asc') }}">
            </div>
        </form>
    </div>

    @if($students->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-6 text-center text-gray-500">
            No students found.
        </div>
    @else
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('students.index', ['sort' => 'first_name', 'direction' => request('sort') == 'first_name' && request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}" 
                                   class="hover:text-gray-700">First Name</a>
                                @if(request('sort') == 'first_name')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ request('direction') == 'asc' ? '8 16l4-4 4 4' : '8 8l4 4 4-4' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center gap-1">
                                <a href="{{ route('students.index', ['sort' => 'last_name', 'direction' => request('sort', 'last_name') == 'last_name' && request('direction') == 'asc' ? 'desc' : 'asc'] + request()->except(['sort', 'direction'])) }}"
                                   class="hover:text-gray-700">Last Name</a>
                                @if(request('sort', 'last_name') == 'last_name')
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M{{ request('direction') == 'asc' ? '8 16l4-4 4 4' : '8 8l4 4 4-4' }}"></path>
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($students as $student)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->first_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->last_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $student->class->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('students.show', $student) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="{{ route('students.analytics', $student) }}" class="text-green-600 hover:text-green-900 mr-3">Analytics</a>
                            <a href="{{ route('students.edit', $student) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                            <form action="{{ route('students.destroy', $student) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this student?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="mt-4">
        {{ $students->links() }}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('search');
    let searchTimeout;

    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        const currentValue = e.target.value;
        const currentPosition = e.target.selectionStart;
        
        searchTimeout = setTimeout(() => {
            const formData = new FormData(searchForm);
            const queryString = new URLSearchParams(formData).toString();
            const currentUrl = window.location.pathname + '?' + queryString;
            
            window.location.href = currentUrl;
        }, 1000); // Increased to 1 second
    });
});

function clearSearch() {
    const searchInput = document.getElementById('search');
    searchInput.value = '';
    document.getElementById('searchForm').submit();
}
</script>
@endpush

@endsection 