@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('program-kerja.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Program Kerja</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Detail</span>
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $programKerja->nama }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail program kerja / kegiatan.</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('program-kerja.edit', $programKerja) }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
            <x-heroicon-o-pencil-square class="h-4 w-4" /> Edit
        </a>
    </div>
@endsection

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        {{-- Info Card --}}
        <div class="glass-card p-6">
            <div class="grid gap-6 sm:grid-cols-2">
                {{-- Status --}}
                <div class="sm:col-span-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                          style="background-color: {{ $programKerja->status_color }}22; color: {{ $programKerja->status_color }}">
                        <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $programKerja->status_color }}"></span>
                        {{ $programKerja->status_label }}
                    </span>
                </div>

                {{-- Kategori --}}
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Kategori</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($programKerja->kategori)
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                                  style="background-color: {{ $programKerja->kategori->warna }}20; color: {{ $programKerja->kategori->warna }}">
                                <span class="h-2 w-2 rounded-full" style="background-color: {{ $programKerja->kategori->warna }}"></span>
                                {{ $programKerja->kategori->nama }}
                            </span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </dd>
                </div>

                {{-- Departemen --}}
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Departemen</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $programKerja->departemen->nama ?? 'BPH / Umum' }}</dd>
                </div>

                {{-- Tanggal --}}
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Tanggal</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        @if($programKerja->tanggal_mulai)
                            {{ $programKerja->tanggal_mulai->translatedFormat('d M Y') }}
                            @if($programKerja->tanggal_selesai && !$programKerja->tanggal_mulai->eq($programKerja->tanggal_selesai))
                                &mdash; {{ $programKerja->tanggal_selesai->translatedFormat('d M Y') }}
                            @endif
                        @else
                            <span class="text-gray-400">Belum ditentukan</span>
                        @endif
                    </dd>
                </div>

                {{-- Lokasi --}}
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Lokasi</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $programKerja->lokasi ?? '-' }}</dd>
                </div>

                {{-- Kepengurusan --}}
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Kepengurusan</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $programKerja->kepengurusan->nama ?? '-' }}</dd>
                </div>

                {{-- Deskripsi --}}
                @if($programKerja->deskripsi)
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Deskripsi</dt>
                    <dd class="mt-1 text-sm leading-relaxed text-gray-900 dark:text-gray-100">{!! nl2br(e($programKerja->deskripsi)) !!}</dd>
                </div>
                @endif

                {{-- Catatan --}}
                @if($programKerja->catatan)
                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Catatan</dt>
                    <dd class="mt-1 text-sm leading-relaxed text-gray-700 dark:text-gray-300">{!! nl2br(e($programKerja->catatan)) !!}</dd>
                </div>
                @endif
            </div>
        </div>

        {{-- Dokumentasi --}}
        <div class="glass-card p-6">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Dokumentasi Kegiatan</h3>

            @if($programKerja->dokumentasi->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4"
                     x-data="{ lightbox: false, imgSrc: '' }">
                    @foreach($programKerja->dokumentasi as $doc)
                        <div class="group cursor-pointer overflow-hidden rounded-xl border border-gray-100 dark:border-gray-700"
                             @click="imgSrc = '{{ asset('storage/' . $doc->file_path) }}'; lightbox = true">
                            <div class="relative h-32 overflow-hidden">
                                <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->judul ?? 'Dokumentasi' }}"
                                     class="h-full w-full object-cover transition duration-300 group-hover:scale-110">
                                <div class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition group-hover:opacity-100">
                                    <x-heroicon-o-magnifying-glass-plus class="h-6 w-6 text-white" />
                                </div>
                            </div>
                            @if($doc->judul)
                                <div class="px-2 py-1.5">
                                    <p class="truncate text-xs text-gray-600 dark:text-gray-400">{{ $doc->judul }}</p>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    {{-- Lightbox --}}
                    <div x-show="lightbox" x-transition.opacity
                         @click.self="lightbox = false" @keydown.escape.window="lightbox = false"
                         class="fixed inset-0 z-[60] flex items-center justify-center bg-black/80 p-4" style="display:none">
                        <button @click="lightbox = false" class="absolute right-4 top-4 text-white hover:text-gray-300">
                            <x-heroicon-o-x-mark class="h-8 w-8" />
                        </button>
                        <img :src="imgSrc" class="max-h-[90vh] max-w-full rounded-lg object-contain shadow-2xl">
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                    <x-heroicon-o-camera class="mb-2 h-10 w-10" />
                    <p class="text-sm">Belum ada dokumentasi.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
