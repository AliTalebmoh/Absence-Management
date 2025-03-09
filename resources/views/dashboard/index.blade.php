@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between space-y-2">
        <h2 class="text-3xl font-bold tracking-tight">Dashboard</h2>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <!-- Total Students Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6 flex flex-col space-y-2">
                <h3 class="font-semibold leading-none tracking-tight">Total Students</h3>
                <p class="text-4xl font-bold text-primary">{{ $totalStudents }}</p>
            </div>
        </div>

        <!-- Total Classes Card -->
        <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
            <div class="p-6 flex flex-col space-y-2">
                <h3 class="font-semibold leading-none tracking-tight">Total Classes</h3>
                <p class="text-4xl font-bold text-primary">{{ $classes->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Classes Overview -->
    <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
        <div class="p-6 space-y-4">
            <h3 class="font-semibold leading-none tracking-tight">Classes Overview</h3>
            <div class="space-y-4">
                @foreach($classes as $class)
                <div class="flex items-center justify-between">
                    <span class="font-medium">{{ $class->name }}</span>
                    <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-primary/10 text-primary">
                        {{ $class->students_count }} students
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection 