@extends('layouts.guest')

@section('title', 'Verifikasi Kartu Anggota BEM - STMIK Dharmapala Riau')

@section('logo')
    <div class="flex flex-col items-center gap-2">
        <img src="{{ asset('assets/image/logo.png') }}" class="h-16 w-16 object-contain" alt="BEM Logo">
        <h1 class="text-sm font-bold tracking-widest text-gray-900 uppercase dark:text-white mt-1">BEM STMIK DHARMAPALA RIAU</h1>
        <p class="text-xs text-gray-500 dark:text-gray-400">Sistem Informasi Keanggotaan BEM (SISFOR-BEM)</p>
    </div>
@endsection

@section('content')
    @if ($success && $activeKeanggotaan)
        <div class="text-center">
            {{-- Success Badge Banner --}}
            <div class="mb-5 flex flex-col items-center justify-center rounded-2xl bg-success-50 dark:bg-success-950/30 p-4 border border-success-200 dark:border-success-850/20 text-success-700 dark:text-success-400">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-100 dark:bg-success-900/50 mb-2.5 shadow-sm">
                    <x-heroicon-s-shield-check class="h-7 w-7 text-success-600 dark:text-success-400" />
                </div>
                <h3 class="text-md font-extrabold tracking-wide uppercase">KTA Terverifikasi</h3>
                <p class="text-xs font-medium text-success-600 dark:text-success-400/80 mt-1">Status Keanggotaan: AKTIF</p>
            </div>

            {{-- Member Info --}}
            <div class="flex flex-col items-center gap-4 mb-6">
                {{-- Foto --}}
                <div class="relative w-28 h-36 rounded-xl overflow-hidden border-2 border-primary-500/20 shadow-lg bg-gray-150 dark:bg-gray-900 flex-shrink-0">
                    @if ($anggota->foto)
                        <img src="{{ asset('storage/' . $anggota->foto) }}" class="w-full h-full object-cover" alt="Foto Profil">
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-gray-200 to-gray-300 dark:from-slate-800 dark:to-slate-900 text-gray-400 dark:text-slate-500">
                            <span class="text-2xl font-bold">{{ $anggota->inisial }}</span>
                        </div>
                    @endif
                </div>

                {{-- Data Text --}}
                <div class="w-full space-y-3 text-left">
                    <div class="border-b border-gray-100 pb-2 dark:border-gray-700/50">
                        <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">Nama Lengkap</span>
                        <p class="text-sm font-bold text-gray-900 dark:text-white uppercase">{{ $anggota->nama }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-2 dark:border-gray-700/50">
                        <div>
                            <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">NIM</span>
                            <p class="text-sm font-mono font-bold text-primary-600 dark:text-primary-400">{{ $anggota->nim }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">Angkatan</span>
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $anggota->angkatan ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-2 dark:border-gray-700/50">
                        <div>
                            <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">Jabatan</span>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $activeKeanggotaan->jabatan?->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">Departemen</span>
                            <p class="text-sm font-bold text-gray-800 dark:text-gray-200">{{ $activeKeanggotaan->departemen?->nama ?? 'Pengurus Inti' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-2 dark:border-gray-700/50">
                        <div>
                            <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">Program Studi</span>
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $anggota->prodi ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[9px] font-bold text-gray-450 dark:text-gray-400 uppercase tracking-wider">Periode BEM</span>
                            <p class="text-xs font-semibold text-amber-600 dark:text-amber-400">{{ $activeKeanggotaan->kepengurusan?->periode ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trust Text --}}
            <p class="text-[11px] text-gray-500 dark:text-gray-400 italic mb-5 leading-normal">
                "Pemegang kartu ini secara resmi terdaftar sebagai Pengurus/Anggota aktif Badan Eksekutif Mahasiswa STMIK Dharmapala Riau untuk periode yang tertera."
            </p>
        </div>
    @else
        <div class="text-center">
            {{-- Error Banner --}}
            <div class="mb-5 flex flex-col items-center justify-center rounded-2xl bg-danger-50 dark:bg-danger-950/30 p-4 border border-danger-200 dark:border-danger-850/20 text-danger-700 dark:text-danger-400">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-danger-100 dark:bg-danger-900/50 mb-2.5 shadow-sm">
                    <x-heroicon-o-x-circle class="h-7 w-7 text-danger-600 dark:text-danger-400" />
                </div>
                <h3 class="text-md font-extrabold tracking-wide uppercase">Verifikasi Gagal</h3>
                <p class="text-xs font-medium text-danger-600 dark:text-danger-400/80 mt-1">Kartu Anggota Tidak Valid</p>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 leading-relaxed">
                {{ $message ?? 'Data keanggotaan BEM tidak ditemukan atau status anggota sudah tidak aktif.' }}
            </p>
        </div>
    @endif

    {{-- Back action button --}}
    <div class="mt-4 pt-4 border-t border-gray-150 dark:border-gray-700 flex justify-center">
        @auth
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                <x-heroicon-s-arrow-left class="h-3.5 w-3.5" />
                Kembali ke Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-1 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:underline">
                <x-heroicon-s-arrow-right-on-rectangle class="h-3.5 w-3.5" />
                Masuk ke Sistem SISFOR-BEM
            </a>
        @endauth
    </div>
@endsection
