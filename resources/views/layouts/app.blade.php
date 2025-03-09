<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Absence Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background font-sans antialiased">
    <div class="relative flex min-h-screen flex-col">
        @auth
        <!-- Navigation -->
        <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
            <div class="container flex h-14 items-center">
                <div class="mr-4 flex">
                    <a href="{{ route('dashboard') }}" class="mr-6 flex items-center space-x-2">
                        <span class="font-bold">{{ config('app.name', 'Absence Management') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="flex gap-6">
                    <a href="{{ route('dashboard') }}" 
                       class="text-sm font-medium transition-colors hover:text-primary {{ request()->routeIs('dashboard') ? 'text-foreground' : 'text-foreground/60' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('students.index') }}"
                       class="text-sm font-medium transition-colors hover:text-primary {{ request()->routeIs('students.*') ? 'text-foreground' : 'text-foreground/60' }}">
                        Students
                    </a>
                    <a href="{{ route('classes.index') }}"
                       class="text-sm font-medium transition-colors hover:text-primary {{ request()->routeIs('classes.*') ? 'text-foreground' : 'text-foreground/60' }}">
                        Classes
                    </a>
                    <a href="{{ route('absences.index') }}"
                       class="text-sm font-medium transition-colors hover:text-primary {{ request()->routeIs('absences.*') ? 'text-foreground' : 'text-foreground/60' }}">
                        Absences
                    </a>
                </div>

                <div class="flex flex-1 items-center justify-end space-x-4">
                    <nav class="flex items-center space-x-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                                Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
        </header>
        @endauth

        <!-- Page Content -->
        <main class="flex-1">
            <div class="container py-6">
                @if(session('success'))
                <div class="mb-6">
                    <div class="rounded-lg border bg-green-50 p-4 text-sm text-green-600 dark:bg-green-500/10 dark:text-green-400 [&>svg]:h-5 [&>svg]:w-5 [&>svg]:stroke-current">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-6">
                    <div class="rounded-lg border bg-destructive/10 p-4 text-sm text-destructive [&>svg]:h-5 [&>svg]:w-5 [&>svg]:stroke-current">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html> 