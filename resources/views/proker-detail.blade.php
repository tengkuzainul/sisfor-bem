<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $programKerja->nama }} — BEM SISFOR</title>

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
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white text-gray-900 antialiased dark:bg-gray-950 dark:text-gray-100" x-data x-cloak>

    {{-- Navbar --}}
    <nav class="fixed top-0 left-0 right-0 z-50 border-b border-gray-200/60 bg-white/80 backdrop-blur-xl dark:border-gray-800/60 dark:bg-gray-950/80">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ URL::asset('assets/image/logo.png') }}" alt="Logo BEM" class="h-12 w-12 object-contain rounded-lg">
                <div>
                    <span class="text-base font-bold tracking-tight text-gray-900 dark:text-white uppercase">Badan Eksekutif Mahasiswa</span>
                    <span class="hidden text-[10px] font-medium tracking-wider text-gray-400 sm:block dark:text-gray-500 uppercase">STMIK DHARMAPALA RIAU</span>
                </div>
            </a>

            <div class="flex items-center gap-3">
                {{-- Dark Mode Toggle --}}
                <button x-data="{ dark: document.documentElement.classList.contains('dark') }"
                        x-init="$watch('dark', val => { localStorage.setItem('theme', val ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', val); })"
                        @click="dark = !dark"
                        class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                    <svg class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                    <svg class="hidden h-5 w-5 dark:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                </button>

                <a href="{{ url('/') }}#program-kerja" class="inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3"/></svg>
                    Kembali
                </a>
            </div>
        </div>
    </nav>

    {{-- Hero Header --}}
    <div class="relative overflow-hidden pt-16">
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 h-[350px] w-[700px] rounded-full bg-gradient-to-br from-primary-400/15 to-accent-400/15 blur-3xl dark:from-primary-900/20 dark:to-accent-900/20"></div>
        </div>

        <div class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <nav class="mb-6 flex items-center gap-2 text-sm text-gray-400 dark:text-gray-500">
                <a href="{{ route('home') }}" class="hover:text-gray-600 dark:hover:text-gray-300">Beranda</a>
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <a href="{{ route('home') }}#program-kerja" class="hover:text-gray-600 dark:hover:text-gray-300">Program Kerja</a>
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                <span class="text-gray-600 dark:text-gray-300">Detail</span>
            </nav>

            {{-- Top Meta --}}
            <div class="flex flex-wrap items-center gap-3">
                @if($programKerja->kategori)
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                          style="background-color: {{ $programKerja->kategori->warna }}20; color: {{ $programKerja->kategori->warna }}">
                        <span class="h-2 w-2 rounded-full" style="background-color: {{ $programKerja->kategori->warna }}"></span>
                        {{ $programKerja->kategori->nama }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                      style="background-color: {{ $programKerja->status_color }}22; color: {{ $programKerja->status_color }}">
                    <span class="h-2 w-2 rounded-full" style="background-color: {{ $programKerja->status_color }}"></span>
                    {{ $programKerja->status_label }}
                </span>
            </div>

            {{-- Title --}}
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl dark:text-white">{{ $programKerja->nama }}</h1>

            {{-- Info Grid --}}
            <div class="mt-6 flex flex-wrap gap-6 text-sm text-gray-500 dark:text-gray-400">
                @if($programKerja->tanggal_mulai)
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        {{ $programKerja->tanggal_mulai->translatedFormat('d F Y') }}
                        @if($programKerja->tanggal_selesai && !$programKerja->tanggal_mulai->eq($programKerja->tanggal_selesai))
                            &mdash; {{ $programKerja->tanggal_selesai->translatedFormat('d F Y') }}
                        @endif
                    </div>
                @endif

                @if($programKerja->lokasi)
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 0115 0z"/></svg>
                        {{ $programKerja->lokasi }}
                    </div>
                @endif

                @if($programKerja->departemen)
                    <div class="flex items-center gap-2">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                        {{ $programKerja->departemen->nama }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Content --}}
    <div class="mx-auto max-w-4xl px-4 pb-20 sm:px-6 lg:px-8">
        <div class="space-y-10">
            {{-- Deskripsi --}}
            @if($programKerja->deskripsi)
                <div class="rounded-2xl border border-gray-200/60 bg-white p-6 sm:p-8 dark:border-gray-800 dark:bg-gray-900">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Deskripsi</h2>
                    <div class="mt-4 leading-relaxed text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($programKerja->deskripsi)) !!}
                    </div>
                </div>
            @endif

            {{-- Catatan --}}
            @if($programKerja->catatan)
                <div class="rounded-2xl border border-warning-200/60 bg-warning-50/50 p-6 sm:p-8 dark:border-warning-900/40 dark:bg-warning-950/20">
                    <h2 class="flex items-center gap-2 text-lg font-bold text-warning-700 dark:text-warning-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                        Catatan
                    </h2>
                    <div class="mt-3 leading-relaxed text-warning-800/70 dark:text-warning-300/70">
                        {!! nl2br(e($programKerja->catatan)) !!}
                    </div>
                </div>
            @endif

            {{-- Dokumentasi --}}
            @if($programKerja->dokumentasi->count() > 0)
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dokumentasi Kegiatan</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $programKerja->dokumentasi->count() }} foto dokumentasi</p>

                    <div x-data="{ lightbox: false, current: 0, images: {{ $programKerja->dokumentasi->map(fn($d) => ['src' => asset('storage/' . $d->file_path), 'title' => $d->judul ?? ''])->values()->toJson() }} }" class="mt-6">
                        {{-- Gallery Grid --}}
                        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                            @foreach($programKerja->dokumentasi as $idx => $doc)
                                <div class="group cursor-pointer overflow-hidden rounded-xl border border-gray-100 dark:border-gray-800"
                                     @click="current = {{ $idx }}; lightbox = true">
                                    <div class="relative aspect-square overflow-hidden">
                                        <img src="{{ asset('storage/' . $doc->file_path) }}"
                                             alt="{{ $doc->judul ?? 'Dokumentasi ' . ($idx+1) }}"
                                             class="h-full w-full object-cover transition duration-300 group-hover:scale-110"
                                             loading="lazy">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition group-hover:opacity-100">
                                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/></svg>
                                        </div>
                                    </div>
                                    @if($doc->judul)
                                        <div class="px-2.5 py-2">
                                            <p class="truncate text-xs font-medium text-gray-600 dark:text-gray-400">{{ $doc->judul }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Lightbox Overlay --}}
                        <div x-show="lightbox" x-transition.opacity
                             @keydown.escape.window="lightbox = false"
                             @keydown.left.window="current = (current - 1 + images.length) % images.length"
                             @keydown.right.window="current = (current + 1) % images.length"
                             class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90 p-4" style="display:none">

                            {{-- Close --}}
                            <button @click="lightbox = false" class="absolute right-4 top-4 z-10 rounded-full bg-white/10 p-2 text-white backdrop-blur-sm transition hover:bg-white/20">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            {{-- Prev --}}
                            <button @click="current = (current - 1 + images.length) % images.length"
                                    class="absolute left-4 z-10 rounded-full bg-white/10 p-3 text-white backdrop-blur-sm transition hover:bg-white/20">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                            </button>

                            {{-- Image --}}
                            <img :src="images[current].src" :alt="images[current].title"
                                 class="max-h-[85vh] max-w-[90vw] rounded-lg object-contain shadow-2xl"
                                 @click.stop>

                            {{-- Next --}}
                            <button @click="current = (current + 1) % images.length"
                                    class="absolute right-4 z-10 rounded-full bg-white/10 p-3 text-white backdrop-blur-sm transition hover:bg-white/20">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </button>

                            {{-- Counter + Title --}}
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-center">
                                <p class="text-sm font-medium text-white" x-text="images[current].title || ''"></p>
                                <p class="mt-1 text-xs text-white/60" x-text="(current + 1) + ' / ' + images.length"></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <footer class="border-t border-gray-200/60 bg-gray-50 py-8 dark:border-gray-800 dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <p class="text-xs text-gray-400 dark:text-gray-500">&copy; {{ date('Y') }} BEM Sistem Informasi. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>
