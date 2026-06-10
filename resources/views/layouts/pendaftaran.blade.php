<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E- Rekrutmen — BEM STMIK Dharmapala Riau</title>

    <link rel="shortcut icon" href="{{ URL::asset('assets/image/logo.png') }}" type="image/x-icon">


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

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100">

    {{-- Navbar --}}
    <nav class="border-b border-gray-200/60 bg-white/80 backdrop-blur-xl dark:border-gray-800/60 dark:bg-gray-950/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ URL::asset('assets/image/logo.png') }}" alt="Logo BEM"
                    class="h-12 w-12 object-contain rounded-lg">
                <div>
                    <span class="text-base font-bold tracking-tight text-gray-900 dark:text-white uppercase">Badan
                        Eksekutif Mahasiswa</span>
                    <span
                        class="hidden text-[10px] font-medium tracking-wider text-gray-400 sm:block dark:text-gray-500 uppercase">STMIK
                        DHARMAPALA RIAU</span>
                </div>
            </a>
            <a href="{{ url('/') }}#rekrutmen"
                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                </svg>
                Kembali
            </a>
        </div>
    </nav>

    {{-- Content --}}
    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('error'))
            <div
                class="mb-6 rounded-lg border border-danger-200 bg-danger-50 p-4 text-sm text-danger-700 dark:border-danger-800 dark:bg-danger-900/30 dark:text-danger-400">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 bg-white py-6 dark:border-gray-800 dark:bg-gray-950">
        <div class="mx-auto max-w-7xl px-4 text-center text-xs text-gray-400 dark:text-gray-500">
            &copy; {{ date('Y') }} BEM Sistem Informasi. All rights reserved.
        </div>
    </footer>
</body>

</html>
