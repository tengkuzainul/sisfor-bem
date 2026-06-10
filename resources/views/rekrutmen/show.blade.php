@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('rekrutmen.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Rekrutmen</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $rekrutmen->judul }}</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $rekrutmen->judul }}</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-semibold"
                      style="background: {{ $rekrutmen->status_color }}15; color: {{ $rekrutmen->status_color }}">
                    <span class="h-1.5 w-1.5 rounded-full" style="background: {{ $rekrutmen->status_color }}"></span>
                    {{ $rekrutmen->status_label }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $rekrutmen->kepengurusan->nama ?? '-' }} &bull;
                {{ $rekrutmen->tanggal_mulai->format('d M Y') }} – {{ $rekrutmen->tanggal_berakhir->format('d M Y') }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Status Change Dropdown --}}
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <x-heroicon-o-arrow-path class="h-4 w-4" />
                    Ubah Status
                </button>
                <div x-show="open" x-cloak @click.outside="open = false"
                     x-transition
                     class="absolute right-0 top-full z-10 mt-1 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                    @foreach(\App\Models\Rekrutmen::STATUSES as $val => $label)
                        @if($val !== $rekrutmen->status)
                            <form method="POST" action="{{ route('rekrutmen.update-status', $rekrutmen) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $val }}">
                                <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                    {{ $label }}
                                </button>
                            </form>
                        @endif
                    @endforeach
                </div>
            </div>
            <a href="{{ route('rekrutmen.edit', $rekrutmen) }}"
               class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <x-heroicon-o-pencil-square class="h-4 w-4" />
                Edit
            </a>
        </div>
    </div>
@endsection

@section('content')
    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-7">
        @php
            $statConfig = [
                ['key' => 'total',     'label' => 'Total',     'color' => '#6b7280', 'icon' => 'heroicon-o-users'],
                ['key' => 'mendaftar', 'label' => 'Mendaftar', 'color' => '#6b7280', 'icon' => 'heroicon-o-document-plus'],
                ['key' => 'review',    'label' => 'Review',    'color' => '#8b5cf6', 'icon' => 'heroicon-o-eye'],
                ['key' => 'wawancara', 'label' => 'Wawancara', 'color' => '#f59e0b', 'icon' => 'heroicon-o-chat-bubble-left-right'],
                ['key' => 'diterima',  'label' => 'Diterima',  'color' => '#10b981', 'icon' => 'heroicon-o-check-circle'],
                ['key' => 'ditolak',   'label' => 'Ditolak',   'color' => '#ef4444', 'icon' => 'heroicon-o-x-circle'],
                ['key' => 'cadangan',  'label' => 'Cadangan',  'color' => '#3b82f6', 'icon' => 'heroicon-o-clock'],
            ];
        @endphp
        @foreach($statConfig as $sc)
            <div class="glass-card p-4 text-center">
                <x-dynamic-component :component="$sc['icon']" class="mx-auto h-6 w-6" style="color: {{ $sc['color'] }}" />
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $stats[$sc['key']] }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sc['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Info Section --}}
    <div class="mt-6 grid grid-cols-1 gap-4 lg:grid-cols-3">
        {{-- Description & Requirements --}}
        <div class="lg:col-span-2 space-y-4">
            @if($rekrutmen->deskripsi)
                <div class="glass-card p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Deskripsi</h3>
                    <div class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ $rekrutmen->deskripsi }}</div>
                </div>
            @endif

            @if($rekrutmen->persyaratan)
                <div class="glass-card p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Persyaratan</h3>
                    <div class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        @foreach(explode("\n", $rekrutmen->persyaratan) as $i => $req)
                            @if(trim($req))
                                <div class="flex gap-2 {{ $i > 0 ? 'mt-1.5' : '' }}">
                                    <span class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-primary-100 text-[10px] font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-400">{{ $i + 1 }}</span>
                                    <span>{{ trim($req) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Poster & Quick Info --}}
        <div class="space-y-4">
            @if($rekrutmen->poster)
                <div class="glass-card overflow-hidden">
                    <img src="{{ Storage::url($rekrutmen->poster) }}" alt="Poster" class="w-full">
                </div>
            @endif
            <div class="glass-card p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Info Singkat</h3>
                <dl class="mt-3 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Kepengurusan</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $rekrutmen->kepengurusan->nama ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Mulai</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $rekrutmen->tanggal_mulai->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Berakhir</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $rekrutmen->tanggal_berakhir->format('d M Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500 dark:text-gray-400">Link Pendaftaran</dt>
                        <dd>
                            @if($rekrutmen->is_open)
                                <a href="{{ route('pendaftaran.form', $rekrutmen->slug) }}" target="_blank"
                                   class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">
                                    Buka Form
                                </a>
                            @else
                                <span class="text-xs text-gray-400">Tidak aktif</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- Recent Applicants --}}
    <div class="mt-6 glass-card">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Pendaftar Terbaru</h3>
            @if($rekrutmen->pendaftar->count() > 0)
                <a href="{{ route('pendaftar.index', ['rekrutmen_id' => $rekrutmen->id]) }}"
                   class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">
                    Lihat Semua →
                </a>
            @endif
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($rekrutmen->pendaftar->take(10) as $p)
                <div class="flex items-center justify-between px-6 py-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700 dark:bg-primary-900/50 dark:text-primary-400">
                            {{ strtoupper(substr($p->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $p->nama_lengkap }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $p->nim }} &bull; {{ $p->prodi }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[10px] font-semibold"
                              style="background: {{ $p->status_color }}15; color: {{ $p->status_color }}">
                            <span class="h-1.5 w-1.5 rounded-full" style="background: {{ $p->status_color }}"></span>
                            {{ $p->status_label }}
                        </span>
                        <a href="{{ route('pendaftar.show', $p) }}"
                           class="rounded-lg p-1 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                            <x-heroicon-o-eye class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada pendaftar.
                </div>
            @endforelse
        </div>
    </div>
@endsection
