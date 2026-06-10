<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'BEM STMIK Dharmapala Riau') }}</title>

    <link rel="shortcut icon" href="{{ URL::asset('assets/image/logo.png') }}" type="image/x-icon">


    <!-- Dark Mode: Prevent FOUC - runs BEFORE any rendering -->
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

    <!-- Fonts (preconnect + dns-prefetch for faster loading) -->
    <link rel="dns-prefetch" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body
    class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-primary-50/20 text-gray-900 antialiased dark:bg-gradient-to-br dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 dark:text-gray-100"
    x-data="{ sidebarOpen: false }" x-cloak>

    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        @include('components.dashboard.sidebar')

        {{-- Main Container --}}
        <div class="flex flex-1 flex-col overflow-hidden lg:pl-[17.5rem]">
            {{-- Topbar --}}
            @include('components.dashboard.topbar')

            {{-- Page Content --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden px-4 pt-[4.75rem] pb-6 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    {{-- Page Header --}}
                    @hasSection('page-header')
                        <div class="mb-6">
                            @yield('page-header')
                        </div>
                    @endif

                    {{-- Content --}}
                    @yield('content')
                </div>
            </main>

            {{-- Footer --}}
            <footer class="border-t border-gray-200/80 px-4 py-4 sm:px-6 lg:px-8 dark:border-gray-800">
                <div
                    class="flex flex-col items-center justify-between gap-2 text-xs text-gray-400 sm:flex-row dark:text-gray-500">
                    <p>&copy; {{ date('Y') }} BEM Sistem Informasi. All rights reserved.</p>
                    <p>Built with <span class="text-red-400">&hearts;</span> Laravel & Tailwind CSS</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- Flash Message Toast (auto-trigger dari session) --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toast.fire({
                    icon: 'success',
                    title: @json(session('success'))
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toast.fire({
                    icon: 'error',
                    title: @json(session('error'))
                });
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toast.fire({
                    icon: 'warning',
                    title: @json(session('warning'))
                });
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Toast.fire({
                    icon: 'info',
                    title: @json(session('info'))
                });
            });
        </script>
    @endif

    {{-- SweetAlert2 Delete Confirmation (reusable) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form') || document.getElementById(this.dataset
                        .formId);
                    Swal.fire({
                        title: 'Yakin hapus?',
                        text: 'Data yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed && form) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
