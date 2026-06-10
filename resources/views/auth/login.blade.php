<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login — BEM SISFOR</title>

    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-gray-50 px-4 antialiased dark:bg-gray-950">
    {{-- Background Decoration --}}
    <div class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-br from-primary-400/20 to-accent-400/20 blur-3xl dark:from-primary-900/20 dark:to-accent-900/20"></div>
    </div>

    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="mb-8 text-center">
            <img src="{{ URL::asset('assets/image/logo.png') }}" alt="Logo BEM SISFOR" class="mx-auto h-20 w-20 object-contain rounded-lg">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{config('app.name')}}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sistem Manajemen BEM &mdash; <span class="capitalize">Silahkan masuk untuk melanjutkan</span></p>
        </div>

        {{-- Login Card --}}
        <div class="rounded-2xl border border-gray-200/60 bg-white/80 p-8 shadow-xl backdrop-blur-xl dark:border-gray-800 dark:bg-gray-900/80">
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-danger-50 p-3 text-sm text-danger-700 dark:bg-danger-950/30 dark:text-danger-400">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus required
                           class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                           placeholder="email@example.com">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input type="password" name="password" id="password" required
                           class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                           placeholder="••••••••">
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800">
                        Ingat saya
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    Masuk
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} {{ config('app.name') }} &mdash; All rights reserved.</p>
    </div>
</body>
</html>
