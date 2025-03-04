@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Add New Class</h1>
            <a href="{{ route('classes.index') }}" class="text-indigo-600 hover:text-indigo-900">Back to Classes</a>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('classes.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Class Name</label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ old('name') }}"
                        placeholder="Enter class name">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                        Create Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 