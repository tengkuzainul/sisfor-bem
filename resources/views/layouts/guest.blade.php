<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'BEM STMIK Dharmapala Riau') }}</title>

    <link rel="shortcut icon" href="{{ URL::asset('assets/image/logo.png') }}" type="image/x-icon">

    <!-- Dark Mode: Prevent FOUC -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-100 antialiased dark:bg-gray-900"
      x-cloak>

    <div class="flex min-h-screen flex-col items-center justify-center pt-6 sm:pt-0">
        {{-- Logo --}}
        <div class="mb-6">
            @hasSection('logo')
                @yield('logo')
            @else
                <a href="/" class="text-2xl font-bold text-primary-600">
                    {{ config('app.name', 'BEM SISFOR') }}
                </a>
            @endif
        </div>

        {{-- Card Content --}}
        <div class="mt-4 w-full overflow-hidden bg-white px-6 py-6 shadow-md sm:max-w-md sm:rounded-lg dark:bg-gray-800">
            @yield('content')
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toast.fire({ icon: 'success', title: @json(session('success')) });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toast.fire({ icon: 'error', title: @json(session('error')) });
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
