@extends('layouts.pendaftaran')

@section('content')
    {{-- Hero --}}
    <div class="mb-10 text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 shadow-lg">
            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Open Recruitment</h1>
        <p class="mt-2 text-gray-500 dark:text-gray-400">Bergabunglah bersama BEM Sistem Informasi dan berkontribusi nyata.</p>
    </div>

    {{-- List Open Recruitments --}}
    @if($rekrutmen->isEmpty())
        <div class="mx-auto max-w-lg rounded-2xl border border-gray-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-gray-900">
            <svg class="mx-auto h-12 w-12 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
            <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Belum Ada Rekrutmen Dibuka</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Saat ini tidak ada periode rekrutmen yang sedang dibuka. Pantau terus halaman ini!</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($rekrutmen as $r)
                <div class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-gray-800 dark:bg-gray-900">
                    @if($r->poster)
                        <div class="aspect-video overflow-hidden">
                            <img src="{{ Storage::url($r->poster) }}" alt="{{ $r->judul }}"
                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </div>
                    @else
                        <div class="flex aspect-video items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-950 dark:to-primary-900">
                            <svg class="h-16 w-16 text-primary-300 dark:text-primary-700" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 0 1-1.44-4.282m3.102.069a18.03 18.03 0 0 1-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 0 1 8.835 2.535M10.34 6.66a23.847 23.847 0 0 0 8.835-2.535m0 0A23.74 23.74 0 0 0 18.795 3m.38 1.125a23.91 23.91 0 0 1 1.014 5.395m-1.014 8.855c-.118.38-.245.754-.38 1.125m.38-1.125a23.91 23.91 0 0 0 1.014-5.395m0-3.46c.495.413.811 1.035.811 1.73 0 .695-.316 1.317-.811 1.73m0-3.46a24.347 24.347 0 0 1 0 3.46" />
                            </svg>
                        </div>
                    @endif

                    <div class="p-5">
                        <div class="mb-2 flex items-center gap-2">
                            <span class="inline-flex items-center gap-1 rounded-full bg-success-100 px-2 py-0.5 text-[10px] font-semibold text-success-700 dark:bg-success-900/30 dark:text-success-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-success-500 animate-pulse"></span>
                                Dibuka
                            </span>
                            <span class="text-xs text-gray-400 dark:text-gray-500">{{ $r->pendaftar_count }} pendaftar</span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $r->judul }}</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $r->kepengurusan->nama ?? '' }} &bull;
                            {{ $r->tanggal_mulai->format('d M') }} – {{ $r->tanggal_berakhir->format('d M Y') }}
                        </p>

                        @if($r->deskripsi)
                            <p class="mt-3 line-clamp-3 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                {{ $r->deskripsi }}
                            </p>
                        @endif

                        <a href="{{ route('pendaftaran.form', $r->slug) }}"
                           class="mt-5 flex w-full items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-primary-700">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
