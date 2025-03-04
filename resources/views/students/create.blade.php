@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Add New Student</h1>
            <a href="{{ route('students.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Students</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Student Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('name') }}"
                        placeholder="Enter student name">
                </div>

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

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Create Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 