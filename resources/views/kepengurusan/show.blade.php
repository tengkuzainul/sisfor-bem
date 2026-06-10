@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('kepengurusan.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Kepengurusan</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $kepengurusan->nama }}</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $kepengurusan->nama }}</h1>
                @if($kepengurusan->is_active)
                    <span class="inline-flex items-center gap-1 rounded-full bg-success-100 px-2.5 py-1 text-[10px] font-semibold text-success-700 dark:bg-success-900/40 dark:text-success-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-success-500 animate-pulse"></span> Aktif
                    </span>
                @endif
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Periode {{ $kepengurusan->periode }} &bull; {{ $kepengurusan->tanggal_mulai->format('d M Y') }} – {{ $kepengurusan->tanggal_selesai->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('kepengurusan.edit', $kepengurusan) }}"
           class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
            <x-heroicon-o-pencil-square class="h-4 w-4" />
            Edit
        </a>
    </div>
@endsection

@section('content')
    {{-- Visi Misi --}}
    @if($kepengurusan->visi || $kepengurusan->misi)
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @if($kepengurusan->visi)
                <div class="glass-card p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Visi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">{{ $kepengurusan->visi }}</p>
                </div>
            @endif
            @if($kepengurusan->misi)
                <div class="glass-card p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Misi</h3>
                    <div class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                        @foreach(explode("\n", $kepengurusan->misi) as $i => $misi)
                            @if(trim($misi))
                                <div class="flex gap-2 {{ $i > 0 ? 'mt-1' : '' }}">
                                    <span class="mt-0.5 flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-full bg-primary-100 text-[10px] font-bold text-primary-700 dark:bg-primary-900/40 dark:text-primary-400">{{ $i + 1 }}</span>
                                    <span>{{ trim($misi) }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Departemen List --}}
    <div class="mt-6 glass-card">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Struktur Organisasi</h3>
            <span class="rounded-lg bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                {{ $kepengurusan->departemen->count() }} Departemen
            </span>
        </div>

        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($kepengurusan->departemen as $dept)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $dept->nama }}
                                @if($dept->singkatan)
                                    <span class="ml-1 text-xs font-normal text-gray-400">({{ $dept->singkatan }})</span>
                                @endif
                            </h4>
                            @if($dept->deskripsi)
                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ $dept->deskripsi }}</p>
                            @endif
                        </div>
                        <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[10px] font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            {{ $dept->keanggotaan->count() }} anggota
                        </span>
                    </div>

                    @if($dept->keanggotaan->count())
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($dept->keanggotaan->sortBy('jabatan.level') as $member)
                                <div class="inline-flex items-center gap-1.5 rounded-lg bg-gray-50 px-2.5 py-1.5 text-xs dark:bg-gray-800">
                                    <div class="flex h-5 w-5 items-center justify-center rounded-full bg-primary-100 text-[9px] font-bold text-primary-700 dark:bg-primary-900/50 dark:text-primary-400">
                                        {{ strtoupper(substr($member->anggota->nama, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $member->anggota->nama }}</span>
                                    <span class="text-gray-400 dark:text-gray-500">{{ $member->jabatan->nama }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada departemen di kepengurusan ini.
                </div>
            @endforelse
        </div>
    </div>
@endsection
