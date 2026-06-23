<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BEM STMIK Dharmapala Riau</title>

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
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100" x-data="{ mobileMenu: false }" x-cloak>

    {{-- ============================== NAVBAR ============================== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-gray-200/60 bg-white/80 backdrop-blur-xl dark:border-gray-800/60 dark:bg-gray-950/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            {{-- Logo --}}
            <a href="#hero" class="flex items-center gap-2">
                <img src="{{ URL::asset('assets/image/logo.png') }}" alt="Logo BEM" class="h-12 w-12 object-contain rounded-lg">
                <div>
                    <span class="text-base font-bold tracking-tight text-gray-900 dark:text-white uppercase">Badan Eksekutif Mahasiswa</span>
                    <span class="hidden text-[10px] font-medium tracking-wider text-gray-400 sm:block dark:text-gray-500 uppercase">STMIK DHARMAPALA RIAU</span>
                </div>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden items-center gap-8 md:flex">
                <a href="#hero" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Beranda</a>
                <a href="#tentang" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Tentang</a>
                <a href="#struktur" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Struktur</a>
                <a href="#departemen" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Departemen</a>
                <a href="#anggota" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Anggota</a>
                <a href="#program-kerja" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Program Kerja</a>
                <a href="#rekrutmen" class="text-sm font-medium text-gray-600 transition hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">Rekrutmen</a>
            </div>

            <div class="flex items-center gap-2">
                {{-- Dark Mode Toggle --}}
                <button x-data="{ dark: document.documentElement.classList.contains('dark') }"
                        x-init="$watch('dark', val => { localStorage.setItem('theme', val ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', val); })"
                        @click="dark = !dark"
                        class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                    <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                </button>

                {{-- CTA --}}
                @auth
                    <a href="{{ route('dashboard') }}" class="hidden rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 sm:inline-flex">
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}" class="hidden rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 sm:inline-flex">
                    Login
                </a>
                @endauth

                {{-- Mobile Menu --}}
                <button @click="mobileMenu = !mobileMenu" class="rounded-lg p-2 text-gray-500 md:hidden dark:text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Nav --}}
        <div x-show="mobileMenu" x-transition class="border-t border-gray-200 bg-white px-4 py-3 md:hidden dark:border-gray-800 dark:bg-gray-950">
            <a href="#hero" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Beranda</a>
            <a href="#tentang" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Tentang</a>
            <a href="#struktur" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Struktur</a>
            <a href="#departemen" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Departemen</a>
            <a href="#anggota" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Anggota</a>
            <a href="#program-kerja" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Program Kerja</a>
            <a href="#rekrutmen" @click="mobileMenu = false" class="block rounded-lg px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-900">Rekrutmen</a>
            @auth
                <a href="{{ route('dashboard') }}" class="mt-2 block rounded-lg bg-primary-600 px-3 py-2 text-center text-sm font-medium text-white">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="mt-2 block rounded-lg bg-primary-600 px-3 py-2 text-center text-sm font-medium text-white">Login</a>
            @endauth
        </div>
    </nav>

    {{-- ============================== HERO ============================== --}}
    <section id="hero" class="relative overflow-hidden pt-16">
        {{-- Background decorations --}}
        <div class="absolute inset-0 -z-10">
            {{-- Main gradient blob --}}
            <div class="absolute top-0 left-1/2 -translate-x-1/2 h-[600px] w-[900px] rounded-full bg-gradient-to-br from-primary-400/20 via-accent-400/10 to-primary-300/20 blur-3xl dark:from-primary-900/30 dark:via-accent-900/20 dark:to-primary-800/30"></div>
            {{-- Secondary blobs --}}
            <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-accent-300/15 blur-3xl dark:bg-accent-800/20"></div>
            <div class="absolute top-40 -right-20 h-64 w-64 rounded-full bg-primary-300/15 blur-3xl dark:bg-primary-800/20"></div>
            <div class="absolute bottom-0 left-1/3 h-48 w-48 rounded-full bg-success-300/10 blur-3xl dark:bg-success-800/15"></div>
        </div>

        {{-- Floating decorative shapes --}}
        <div class="absolute inset-0 -z-5 overflow-hidden pointer-events-none" aria-hidden="true">
            {{-- Grid pattern --}}
            <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05]" style="background-image: radial-gradient(circle, currentColor 1px, transparent 1px); background-size: 40px 40px;"></div>

            {{-- Geometric floating elements --}}
            <div class="absolute top-32 left-[8%] h-16 w-16 rounded-xl border border-primary-300/30 rotate-12 dark:border-primary-700/30" style="animation: float 8s ease-in-out infinite;"></div>
            <div class="absolute top-48 right-[12%] h-12 w-12 rounded-full border-2 border-accent-300/25 dark:border-accent-700/25" style="animation: float 6s ease-in-out infinite 1s;"></div>
            <div class="absolute top-[60%] left-[5%] h-8 w-8 rounded-lg bg-primary-400/10 rotate-45 dark:bg-primary-600/15" style="animation: float 7s ease-in-out infinite 2s;"></div>
            <div class="absolute top-[55%] right-[8%] h-14 w-14 rounded-xl border border-success-300/20 -rotate-12 dark:border-success-700/20" style="animation: float 9s ease-in-out infinite 0.5s;"></div>
            <div class="absolute top-[70%] left-[15%] h-6 w-6 rounded-full bg-accent-400/15 dark:bg-accent-600/20" style="animation: float 5s ease-in-out infinite 3s;"></div>
            <div class="absolute top-24 left-[25%] h-3 w-3 rounded-full bg-primary-500/30 dark:bg-primary-400/30" style="animation: float 4s ease-in-out infinite 1.5s;"></div>
            <div class="absolute top-[45%] right-[18%] h-4 w-4 rounded-full bg-warning-400/20 dark:bg-warning-500/25" style="animation: float 6s ease-in-out infinite 2.5s;"></div>

            {{-- Decorative lines --}}
            <svg class="absolute top-20 right-[5%] h-32 w-32 text-primary-300/15 dark:text-primary-600/15" viewBox="0 0 100 100" fill="none">
                <path d="M20 80 Q50 20 80 80" stroke="currentColor" stroke-width="1.5"/>
                <path d="M10 60 Q50 10 90 60" stroke="currentColor" stroke-width="1"/>
            </svg>
            <svg class="absolute bottom-32 left-[3%] h-28 w-28 text-accent-300/15 dark:text-accent-600/15" viewBox="0 0 100 100" fill="none">
                <circle cx="50" cy="50" r="35" stroke="currentColor" stroke-width="1"/>
                <circle cx="50" cy="50" r="20" stroke="currentColor" stroke-width="1"/>
            </svg>
        </div>

        <div class="mx-auto flex min-h-[90vh] max-w-7xl flex-col items-center justify-center px-4 text-center sm:px-6 lg:px-8">

            {{-- Tagline pill --}}
            @if($kepengurusan)
                <span class="mb-6 inline-flex items-center gap-2 rounded-full border border-primary-200/80 bg-primary-50/80 px-5 py-2 text-xs font-semibold text-primary-700 backdrop-blur-sm dark:border-primary-800/60 dark:bg-primary-950/50 dark:text-primary-400">
                    <span class="h-1.5 w-1.5 rounded-full bg-primary-500 animate-pulse"></span>
                    Periode {{ $kepengurusan->periode }}
                </span>
            @endif

            {{-- Logo mark --}}
            <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-xl shadow-primary-500/25 sm:h-24 sm:w-24 sm:rounded-3xl">
                <img src="{{ URL::asset('assets/image/logo.png') }}" alt="Logo BEM" class="h-14 w-14 object-contain rounded-lg sm:h-16 sm:w-16">
            </div>

            {{-- Heading --}}
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl dark:text-white">
                BADAN EKSEKUTIF MAHASISWA
                <span class="mt-2 block bg-gradient-to-r from-primary-600 via-primary-500 to-accent-600 bg-clip-text text-transparent dark:from-primary-400 dark:via-primary-300 dark:to-accent-400">
                    STMIK DHARMAPALA RIAU
                </span>
            </h1>

            {{-- Subtitle --}}
            <p class="mx-auto mt-6 max-w-2xl text-lg leading-relaxed text-gray-600 dark:text-gray-400">
                @if($kepengurusan && $kepengurusan->deskripsi)
                    {{ $kepengurusan->deskripsi }}
                @else
                    Organisasi kemahasiswaan yang berfokus pada pengembangan potensi, kolaborasi, dan inovasi mahasiswa Sistem Informasi.
                @endif
            </p>

            {{-- Feature highlights --}}
            <div class="mx-auto mt-8 flex max-w-xl flex-wrap items-center justify-center gap-x-6 gap-y-3 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342"/></svg>
                    Pengembangan Diri
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-accent-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z"/></svg>
                    Kolaborasi Tim
                </span>
                <span class="inline-flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-success-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    Inovasi Kampus
                </span>
            </div>

            {{-- CTA Buttons --}}
            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <a href="#tentang" class="group inline-flex items-center gap-2 rounded-xl bg-primary-600 px-7 py-3.5 text-sm font-semibold text-white shadow-lg shadow-primary-500/30 transition-all hover:bg-primary-700 hover:shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5">
                    Visi & Misi Kami
                    <svg class="h-4 w-4 transition-transform group-hover:translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/></svg>
                </a>
                @if($openRecruitments->count() > 0)
                    <a href="#rekrutmen" class="group inline-flex items-center gap-2 rounded-xl border border-success-300 bg-success-50 px-7 py-3.5 text-sm font-semibold text-success-700 shadow-sm transition-all hover:bg-success-100 hover:-translate-y-0.5 dark:border-success-800 dark:bg-success-950/50 dark:text-success-400 dark:hover:bg-success-950">
                        <span class="relative flex h-2 w-2">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-success-400 opacity-75"></span>
                            <span class="relative inline-flex h-2 w-2 rounded-full bg-success-500"></span>
                        </span>
                        Open Recruitment
                    </a>
                @else
                    <a href="#struktur" class="group inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-7 py-3.5 text-sm font-semibold text-gray-700 shadow-sm transition-all hover:bg-gray-50 hover:-translate-y-0.5 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                        Anggota Pengurus
                        <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                    </a>
                @endif
            </div>

            {{-- Stats --}}
            <div class="mt-16 w-full max-w-3xl">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                    <div class="group rounded-2xl border border-gray-200/60 bg-white/70 p-5 backdrop-blur-sm transition hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900/70">
                        <div class="flex items-center justify-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900/50">
                                <svg class="h-4 w-4 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                            </div>
                        </div>
                        <p class="mt-2 text-2xl font-bold text-primary-600 dark:text-primary-400 sm:text-3xl">{{ $totalAnggota }}</p>
                        <p class="mt-0.5 text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Anggota</p>
                    </div>
                    <div class="group rounded-2xl border border-gray-200/60 bg-white/70 p-5 backdrop-blur-sm transition hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900/70">
                        <div class="flex items-center justify-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-accent-100 dark:bg-accent-900/50">
                                <svg class="h-4 w-4 text-accent-600 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                            </div>
                        </div>
                        <p class="mt-2 text-2xl font-bold text-accent-600 dark:text-accent-400 sm:text-3xl">{{ $departemen->count() }}</p>
                        <p class="mt-0.5 text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Departemen</p>
                    </div>
                    <div class="group rounded-2xl border border-gray-200/60 bg-white/70 p-5 backdrop-blur-sm transition hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900/70">
                        <div class="flex items-center justify-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success-100 dark:bg-success-900/50">
                                <svg class="h-4 w-4 text-success-600 dark:text-success-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                            </div>
                        </div>
                        <p class="mt-2 text-2xl font-bold text-success-600 dark:text-success-400 sm:text-3xl">{{ $jabatan->count() }}</p>
                        <p class="mt-0.5 text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Jabatan</p>
                    </div>
                    <div class="group rounded-2xl border border-gray-200/60 bg-white/70 p-5 backdrop-blur-sm transition hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900/70">
                        <div class="flex items-center justify-center gap-2">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-warning-100 dark:bg-warning-900/50">
                                <svg class="h-4 w-4 text-warning-600 dark:text-warning-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                            </div>
                        </div>
                        <p class="mt-2 text-2xl font-bold text-warning-600 dark:text-warning-400 sm:text-3xl">{{ $kepengurusan ? $kepengurusan->periode : '-' }}</p>
                        <p class="mt-0.5 text-[11px] font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Periode Aktif</p>
                    </div>
                </div>
            </div>

            {{-- Scroll indicator --}}
            <div class="mt-12 flex flex-col items-center gap-2 text-gray-400 dark:text-gray-500">
                <span class="text-[10px] font-medium uppercase tracking-widest">Scroll</span>
                <div class="h-8 w-5 rounded-full border-2 border-gray-300 dark:border-gray-600 flex justify-center pt-1">
                    <div class="h-1.5 w-1 rounded-full bg-gray-400 dark:bg-gray-500 animate-bounce"></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== TENTANG / VISI MISI ============================== --}}
    @if($kepengurusan)
    <section id="tentang" class="border-t border-gray-200/60 bg-gray-50/50 py-20 dark:border-gray-800 dark:bg-gray-900/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">Tentang Kami</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Visi & Misi</h2>
                <p class="mx-auto mt-3 max-w-2xl text-gray-500 dark:text-gray-400">{{ $kepengurusan->nama }}</p>
            </div>

            <div class="mt-12 grid gap-8 lg:grid-cols-2">
                {{-- Visi --}}
                <div class="rounded-2xl border border-gray-200/60 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 inline-flex items-center justify-center rounded-xl bg-primary-50 p-3 dark:bg-primary-950/50">
                        <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Visi</h3>
                    <p class="mt-3 leading-relaxed text-gray-600 dark:text-gray-400">{{ $kepengurusan->visi }}</p>
                </div>

                {{-- Misi --}}
                <div class="rounded-2xl border border-gray-200/60 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="mb-4 inline-flex items-center justify-center rounded-xl bg-accent-50 p-3 dark:bg-accent-950/50">
                        <svg class="h-6 w-6 text-accent-600 dark:text-accent-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Misi</h3>
                    <ul class="mt-3 space-y-2">
                        @foreach(explode("\n", $kepengurusan->misi) as $misi)
                            @if(trim($misi))
                                <li class="flex items-start gap-2 text-gray-600 dark:text-gray-400">
                                    <svg class="mt-1 h-4 w-4 shrink-0 text-success-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    <span>{{ preg_replace('/^\d+\.\s*/', '', trim($misi)) }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- ============================== STRUKTUR / BPH ============================== --}}
    <section id="struktur" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">Struktur Organisasi</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Badan Pengurus Harian</h2>
                <p class="mx-auto mt-3 max-w-2xl text-gray-500 dark:text-gray-400">Pengurus inti yang menjalankan organisasi.</p>
            </div>

            @if($bph->count() > 0)
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($bph as $member)
                        <div class="group relative overflow-hidden rounded-2xl border border-gray-200/60 bg-white p-6 text-center shadow-sm transition hover:shadow-md hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900">
                            {{-- Foto --}}
                            @if($member->anggota->foto)
                                <img src="{{ asset('storage/' . $member->anggota->foto) }}"
                                     alt="{{ $member->anggota->nama }}"
                                     class="mx-auto h-28 w-28 rounded-full object-cover border-4 border-primary-100 dark:border-primary-900/50 shadow-sm">
                            @else
                                <div class="mx-auto flex h-28 w-28 items-center justify-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-3xl font-bold text-white shadow-sm">
                                    {{ strtoupper(substr($member->anggota->nama, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->anggota->nama)[1] ?? '', 0, 1)) }}
                                </div>
                            @endif

                            <h3 class="mt-4 text-base font-bold text-gray-900 dark:text-white">{{ $member->anggota->nama }}</h3>
                            <p class="mt-1 text-sm font-medium text-primary-600 dark:text-primary-400">{{ $member->jabatan->nama ?? '-' }}</p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ $member->anggota->prodi ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-12 text-center text-gray-400 dark:text-gray-500">Belum ada data BPH.</p>
            @endif
        </div>
    </section>

    {{-- ============================== DEPARTEMEN ============================== --}}
    <section id="departemen" class="border-t border-gray-200/60 bg-gray-50/50 py-20 dark:border-gray-800 dark:bg-gray-900/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">Departemen</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Departemen Kami</h2>
                <p class="mx-auto mt-3 max-w-2xl text-gray-500 dark:text-gray-400">Setiap departemen memiliki fokus dan program kerja masing-masing.</p>
            </div>

            @if($departemen->count() > 0)
                <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($departemen as $dept)
                        @php
                            $deptMembers = $anggotaByDept->get($dept->id, collect());
                            $colors = ['from-primary-500 to-primary-700', 'from-accent-500 to-accent-700', 'from-success-500 to-success-700', 'from-warning-500 to-warning-700', 'from-danger-500 to-danger-700'];
                            $color = $colors[$loop->index % count($colors)];
                        @endphp
                        <div class="rounded-2xl border border-gray-200/60 bg-white p-6 shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br {{ $color }} text-sm font-bold text-white shadow-sm">
                                    {{ $dept->singkatan ?? substr($dept->nama, 0, 2) }}
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $dept->nama }}</h3>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $deptMembers->count() }} anggota</p>
                                </div>
                            </div>

                            @if($dept->deskripsi)
                                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ $dept->deskripsi }}</p>
                            @endif

                            {{-- Department head --}}
                            @php $head = $deptMembers->first(); @endphp
                            @if($head)
                                <div class="mt-4 flex items-center gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800/50">
                                    @if($head->anggota->foto)
                                        <img src="{{ asset('storage/' . $head->anggota->foto) }}" alt="{{ $head->anggota->nama }}" class="h-8 w-8 rounded-full object-cover">
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br {{ $color }} text-xs font-bold text-white">
                                            {{ strtoupper(substr($head->anggota->nama, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $head->anggota->nama }}</p>
                                        <p class="text-xs text-gray-400">{{ $head->jabatan->nama ?? '-' }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="mt-12 text-center text-gray-400 dark:text-gray-500">Belum ada data departemen.</p>
            @endif
        </div>
    </section>

    {{-- ============================== ANGGOTA ============================== --}}
    <section id="anggota" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">Anggota</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Seluruh Anggota</h2>
                <p class="mx-auto mt-3 max-w-2xl text-gray-500 dark:text-gray-400">Daftar seluruh anggota di periode kepengurusan aktif.</p>
            </div>

            {{-- Filter by department --}}
            <div x-data="{ activeTab: 'all' }" class="mt-10">
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <button @click="activeTab = 'all'" :class="activeTab === 'all' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800'"
                            class="rounded-full border border-gray-200 px-4 py-1.5 text-xs font-medium transition dark:border-gray-800">
                        Semua ({{ $totalAnggota }})
                    </button>
                    <button @click="activeTab = 'bph'" :class="activeTab === 'bph' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800'"
                            class="rounded-full border border-gray-200 px-4 py-1.5 text-xs font-medium transition dark:border-gray-800">
                        BPH ({{ $bph->count() }})
                    </button>
                    @foreach($departemen as $dept)
                        @php $deptCount = $anggotaByDept->get($dept->id, collect())->count(); @endphp
                        <button @click="activeTab = 'dept-{{ $dept->id }}'" :class="activeTab === 'dept-{{ $dept->id }}' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800'"
                                class="rounded-full border border-gray-200 px-4 py-1.5 text-xs font-medium transition dark:border-gray-800">
                            {{ $dept->singkatan ?? $dept->nama }} ({{ $deptCount }})
                        </button>
                    @endforeach
                </div>

                {{-- All Members --}}
                <div class="mt-8">
                    {{-- BPH Section --}}
                    <div x-show="activeTab === 'all' || activeTab === 'bph'" x-transition>
                        @if($bph->count() > 0)
                            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                @foreach($bph as $member)
                                    @include('partials._member-card', ['member' => $member, 'badge' => 'BPH'])
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Per Departemen --}}
                    @foreach($departemen as $dept)
                        @php $deptMembers = $anggotaByDept->get($dept->id, collect()); @endphp
                        <div x-show="activeTab === 'all' || activeTab === 'dept-{{ $dept->id }}'" x-transition>
                            @if($deptMembers->count() > 0)
                                @if($departemen->count() > 0)
                                    <div x-show="activeTab === 'all'" class="mt-8 mb-4">
                                        <h3 class="text-sm font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">{{ $dept->nama }}</h3>
                                    </div>
                                @endif
                                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" :class="activeTab !== 'all' ? 'mt-0' : ''">
                                    @foreach($deptMembers as $member)
                                        @include('partials._member-card', ['member' => $member, 'badge' => $dept->singkatan ?? $dept->nama])
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== PROGRAM KERJA / KALENDER ============================== --}}
    <section id="program-kerja" class="border-t border-gray-200/60 bg-gray-50/50 py-20 dark:border-gray-800 dark:bg-gray-900/50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">Timeline</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Program Kerja</h2>
                <p class="mx-auto mt-3 max-w-2xl text-gray-500 dark:text-gray-400">Kalender kegiatan dan program kerja {{ config('app.name') }}.</p>
            </div>

            {{-- Calendar --}}
            <div class="mt-12" x-data="prokerCalendar()" x-init="init()">
                {{-- Calendar Header --}}
                <div class="flex items-center justify-between rounded-t-2xl border border-gray-200/60 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
                    <button @click="prevMonth()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                    </button>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white" x-text="monthYearLabel"></h3>
                    <button @click="nextMonth()" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>

                {{-- Day headers --}}
                <div class="grid grid-cols-7 border-x border-gray-200/60 bg-gray-50 dark:border-gray-800 dark:bg-gray-800/50">
                    <template x-for="day in ['Sen','Sel','Rab','Kam','Jum','Sab','Min']">
                        <div class="py-2 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400" x-text="day"></div>
                    </template>
                </div>

                {{-- Calendar Grid --}}
                <div class="grid grid-cols-7 border-x border-b border-gray-200/60 rounded-b-2xl overflow-hidden dark:border-gray-800">
                    <template x-for="(cell, idx) in calendarCells" :key="idx">
                        <div class="relative min-h-[80px] sm:min-h-[100px] border-t border-r border-gray-100 p-1.5 sm:p-2 dark:border-gray-800"
                             :class="cell.isCurrentMonth ? 'bg-white dark:bg-gray-900' : 'bg-gray-50/50 dark:bg-gray-950/30'">
                            <span class="text-xs font-medium"
                                  :class="cell.isToday ? 'flex h-6 w-6 items-center justify-center rounded-full bg-primary-600 text-white' : (cell.isCurrentMonth ? 'text-gray-700 dark:text-gray-300' : 'text-gray-300 dark:text-gray-600')"
                                  x-text="cell.day"></span>
                            {{-- Events on this day --}}
                            <div class="mt-1 space-y-0.5">
                                <template x-for="ev in cell.events" :key="ev.id">
                                    <a :href="ev.url" class="block truncate rounded px-1.5 py-0.5 text-[10px] sm:text-xs font-medium leading-tight transition hover:opacity-80"
                                       :style="'background-color:' + ev.color + '22; color:' + ev.color"
                                       x-text="ev.title"></a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Legend --}}
                <div class="mt-4 flex flex-wrap items-center justify-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-info-500"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Coming Soon</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-success-500"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Berlangsung</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-warning-500"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Pending</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-gray-400"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Selesai</span>
                    </div>
                </div>
            </div>

            {{-- Upcoming Events --}}
            @if($upcomingProker->count() > 0)
            <div class="mt-16">
                <h3 class="text-center text-xl font-bold text-gray-900 dark:text-white">Kegiatan Mendatang</h3>
                <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($upcomingProker as $proker)
                        <a href="{{ route('home.proker.detail', $proker) }}" class="group rounded-2xl border border-gray-200/60 bg-white p-5 shadow-sm transition hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    @if($proker->kategori)
                                        <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-medium"
                                              style="background-color: {{ $proker->kategori->warna }}20; color: {{ $proker->kategori->warna }}">
                                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $proker->kategori->warna }}"></span>
                                            {{ $proker->kategori->nama }}
                                        </span>
                                    @endif
                                    <h4 class="mt-2 font-bold text-gray-900 group-hover:text-primary-600 dark:text-white dark:group-hover:text-primary-400">{{ $proker->nama }}</h4>
                                </div>
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                      style="background-color: {{ $proker->status_color }}22; color: {{ $proker->status_color }}">
                                    <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $proker->status_color }}"></span>
                                    {{ $proker->status_label }}
                                </span>
                            </div>
                            @if($proker->tanggal_mulai)
                                <p class="mt-3 flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    {{ $proker->tanggal_mulai->translatedFormat('d M Y') }}
                                    @if($proker->tanggal_selesai && !$proker->tanggal_mulai->eq($proker->tanggal_selesai))
                                        &mdash; {{ $proker->tanggal_selesai->translatedFormat('d M Y') }}
                                    @endif
                                </p>
                            @endif
                            @if($proker->lokasi)
                                <p class="mt-1 flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0115 0z"/></svg>
                                    {{ $proker->lokasi }}
                                </p>
                            @endif
                            @if($proker->deskripsi)
                                <p class="mt-2 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">{{ $proker->deskripsi }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>

    {{-- ============================== REKRUTMEN / APPLY ============================== --}}
    <section id="rekrutmen" class="py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <span class="text-xs font-semibold uppercase tracking-widest text-primary-600 dark:text-primary-400">Open Recruitment</span>
                <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">Bergabung Bersama Kami</h2>
                <p class="mx-auto mt-3 max-w-2xl text-gray-500 dark:text-gray-400">Tertarik berkontribusi di {{ config('app.name') }}? Daftarkan dirimu sekarang dan jadilah bagian dari perubahan.</p>
            </div>

            @if($openRecruitments->count() > 0)
                <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($openRecruitments as $recruitment)
                        <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200/60 bg-white shadow-sm transition hover:shadow-lg hover:-translate-y-1 dark:border-gray-800 dark:bg-gray-900">
                            {{-- Poster --}}
                            @if($recruitment->poster)
                                <div class="aspect-[16/9] overflow-hidden">
                                    <img src="{{ Storage::url($recruitment->poster) }}" alt="{{ $recruitment->judul }}"
                                         class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                </div>
                            @else
                                <div class="flex aspect-[16/9] items-center justify-center bg-gradient-to-br from-primary-50 to-accent-50 dark:from-primary-950 dark:to-accent-950">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-primary-300 dark:text-primary-700" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                        </svg>
                                        <p class="mt-2 text-xs font-medium text-primary-400 dark:text-primary-600">Open Recruitment</p>
                                    </div>
                                </div>
                            @endif

                            {{-- Content --}}
                            <div class="flex flex-1 flex-col p-6">
                                {{-- Badge & Stats --}}
                                <div class="mb-3 flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success-100 px-2.5 py-1 text-[11px] font-semibold text-success-700 dark:bg-success-900/30 dark:text-success-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success-500 animate-pulse"></span>
                                        Dibuka
                                    </span>
                                    <span class="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>
                                        {{ $recruitment->pendaftar_count }} pendaftar
                                    </span>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $recruitment->judul }}</h3>

                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                                    {{ $recruitment->tanggal_mulai->format('d M') }} – {{ $recruitment->tanggal_berakhir->format('d M Y') }}
                                </p>

                                @if($recruitment->kepengurusan)
                                    <p class="mt-1 flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                        <svg class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                        {{ $recruitment->kepengurusan->nama }}
                                    </p>
                                @endif

                                @if($recruitment->deskripsi)
                                    <p class="mt-3 line-clamp-3 flex-1 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                        {{ $recruitment->deskripsi }}
                                    </p>
                                @endif

                                {{-- Countdown --}}
                                @php
                                    $sisaHari = now()->diffInDays($recruitment->tanggal_berakhir, false);
                                @endphp
                                @if($sisaHari > 0 && $sisaHari <= 14)
                                    <div class="mt-3 flex items-center gap-1.5 text-xs font-medium text-warning-600 dark:text-warning-400">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        {{ (int) $sisaHari }} hari lagi ditutup
                                    </div>
                                @endif

                                {{-- CTA --}}
                                <a href="{{ route('pendaftaran.form', $recruitment->slug) }}"
                                   class="mt-5 flex w-full items-center justify-center gap-2 rounded-xl bg-primary-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-primary-500/25 transition hover:bg-primary-700 hover:shadow-primary-500/30">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                    Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Link to full list --}}
                <div class="mt-8 text-center">
                    <a href="{{ route('pendaftaran.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 transition hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                        Lihat semua rekrutmen
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                    </a>
                </div>
            @else
                {{-- Empty state --}}
                <div class="mt-12 mx-auto max-w-lg">
                    <div class="rounded-2xl border border-gray-200/60 bg-white p-10 text-center shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 dark:bg-gray-800">
                            <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">Belum Ada Rekrutmen Terbuka</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Saat ini belum ada periode rekrutmen yang dibuka. Pantau terus halaman ini untuk informasi terbaru!</p>
                        <a href="{{ route('pendaftaran.index') }}"
                           class="mt-6 inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                            Lihat Riwayat Rekrutmen
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ============================== FOOTER ============================== --}}
    <footer class="border-t border-gray-200/60 bg-gray-50 py-10 dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <div class="flex items-center gap-3">
                    <img src="{{ URL::asset('assets/image/logo.png') }}" alt="Logo {{ config('app.name') }}" class="h-12 w-12">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">Template by <span class="text-red-400">&hearts; Wok&rdquo;z Developer</span></p>
            </div>
        </div>
    </footer>

    <script>
        function prokerCalendar() {
            return {
                currentDate: new Date(),
                events: [],
                monthYearLabel: '',
                calendarCells: [],

                init() {
                    this.fetchEvents();
                },

                async fetchEvents() {
                    try {
                        const res = await fetch('{{ route("api.calendar-events") }}');
                        this.events = await res.json();
                    } catch (e) {
                        this.events = [];
                    }
                    this.buildCalendar();
                },

                prevMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                    this.buildCalendar();
                },

                nextMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                    this.buildCalendar();
                },

                buildCalendar() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                    this.monthYearLabel = months[month] + ' ' + year;

                    const firstDay = new Date(year, month, 1);
                    let startDow = firstDay.getDay() - 1; // Monday = 0
                    if (startDow < 0) startDow = 6;

                    const daysInMonth = new Date(year, month + 1, 0).getDate();
                    const prevMonthDays = new Date(year, month, 0).getDate();

                    const today = new Date();
                    const todayStr = today.getFullYear() + '-' + String(today.getMonth()+1).padStart(2,'0') + '-' + String(today.getDate()).padStart(2,'0');

                    const cells = [];
                    const totalCells = Math.ceil((startDow + daysInMonth) / 7) * 7;

                    for (let i = 0; i < totalCells; i++) {
                        let day, dateStr, isCurrentMonth = false, isToday = false;

                        if (i < startDow) {
                            day = prevMonthDays - startDow + i + 1;
                            const pm = month === 0 ? 12 : month;
                            const py = month === 0 ? year - 1 : year;
                            dateStr = py + '-' + String(pm).padStart(2,'0') + '-' + String(day).padStart(2,'0');
                        } else if (i >= startDow + daysInMonth) {
                            day = i - startDow - daysInMonth + 1;
                            const nm = month + 2 > 12 ? 1 : month + 2;
                            const ny = month + 2 > 12 ? year + 1 : year;
                            dateStr = ny + '-' + String(nm).padStart(2,'0') + '-' + String(day).padStart(2,'0');
                        } else {
                            day = i - startDow + 1;
                            dateStr = year + '-' + String(month+1).padStart(2,'0') + '-' + String(day).padStart(2,'0');
                            isCurrentMonth = true;
                            isToday = dateStr === todayStr;
                        }

                        // Find events for this date
                        const dayEvents = this.events.filter(ev => {
                            return dateStr >= ev.start && dateStr <= ev.end;
                        });

                        cells.push({ day, dateStr, isCurrentMonth, isToday, events: dayEvents });
                    }

                    this.calendarCells = cells;
                }
            };
        }
    </script>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(var(--tw-rotate, 0)); }
            50% { transform: translateY(-15px) rotate(var(--tw-rotate, 0)); }
        }
    </style>

</body>
</html>
