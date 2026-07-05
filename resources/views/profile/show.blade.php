@extends('layouts.app')

@section('title', 'Profil Saya - BEM STMIK Dharmapala Riau')

@section('breadcrumb')
    <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">Profil Saya</a>
@endsection

@section('page-header')
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil Saya</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi akun Anda dan cetak Kartu Anggota BEM.</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        {{-- Kolom Kiri: Informasi Akun & Ganti Password --}}
        <div class="space-y-6 lg:col-span-5">
            {{-- Info Akun Dasar --}}
            <div class="glass-card p-6">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full text-xl font-bold text-white shadow-md"
                        style="background: linear-gradient(135deg, {{ $user->role_color }}cc, {{ $user->role_color }})">
                        {{ substr($user->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                        <span class="mt-1.5 inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                            style="background-color: {{ $user->role_color }}15; color: {{ $user->role_color }}">
                            <span class="h-1.5 w-1.5 rounded-full" style="background-color: {{ $user->role_color }}"></span>
                            {{ $user->role_label }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Form Ganti Password --}}
            <div class="glass-card p-6">
                <h3 class="text-md mb-4 font-semibold text-gray-900 dark:text-white">Perbarui Keamanan Password</h3>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-950/30">
                        @error('current_password')
                            <p class="mt-1 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password Baru</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-950/30">
                        @error('password')
                            <p class="mt-1 text-xs text-danger-600 dark:text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2 text-sm text-gray-900 outline-none transition focus:border-primary-500 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:border-primary-500 dark:focus:ring-primary-950/30">
                    </div>

                    <button type="submit"
                        class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-primary-500 dark:hover:bg-primary-600">
                        <x-heroicon-o-key class="h-4 w-4" />
                        Perbarui Password
                    </button>
                </form>
            </div>
        </div>

        {{-- Kolom Kanan: Kartu Anggota BEM --}}
        <div class="lg:col-span-7">
            @if ($anggota)
                <div class="glass-card p-6 flex flex-col items-center">
                    <div class="w-full border-b border-gray-200/50 pb-4 mb-6 dark:border-gray-700/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Kartu Anggota BEM</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Klik kartu untuk membalik halaman depan/belakang.</p>
                    </div>

                    {{-- 3D Flippable Card --}}
                    <div x-data="{ flipped: false }" class="perspective-1000 w-full max-w-md aspect-[1.58] mb-6 select-none" @click="flipped = !flipped">
                        <div class="relative w-full h-full transition-transform duration-500 transform-style-3d cursor-pointer"
                            :class="flipped ? 'rotate-y-180' : ''">
                            
                            {{-- FRONT SIDE --}}
                            <div class="absolute inset-0 w-full h-full rounded-2xl overflow-hidden backface-hidden shadow-2xl border border-amber-400/20"
                                style="background: linear-gradient(135deg, oklch(0.379 0.146 265.522), oklch(0.282 0.091 267.935));">
                                {{-- Card Accent Grid & Glow --}}
                                <div class="absolute inset-0 opacity-10 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:16px_16px]"></div>
                                <div class="absolute -right-16 -top-16 w-40 h-40 rounded-full bg-cyan-400/10 blur-2xl"></div>
                                <div class="absolute -left-16 -bottom-16 w-40 h-40 rounded-full bg-amber-400/10 blur-2xl"></div>
                                
                                {{-- Header --}}
                                <div class="p-4 flex items-center justify-between border-b border-white/10 bg-black/10">
                                    <div class="flex items-center gap-2">
                                        <img src="{{ asset('assets/image/logo.png') }}" class="h-8 w-8 object-contain" alt="BEM Logo">
                                        <div class="leading-tight">
                                            <h4 class="text-xs font-bold tracking-wider text-white uppercase">Badan Eksekutif Mahasiswa</h4>
                                            <p class="text-[9px] font-medium text-slate-300">STMIK DHARMAPALA RIAU</p>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-bold uppercase tracking-widest text-amber-400 bg-amber-500/10 px-2 py-0.5 rounded border border-amber-400/20 shadow-inner">
                                        KTA RESMI
                                    </span>
                                </div>

                                {{-- Main Content --}}
                                <div class="p-4 flex gap-4 items-stretch h-[calc(100%-3.5rem)]">
                                    {{-- Photo --}}
                                    <div class="flex-shrink-0 flex flex-col justify-center">
                                        <div class="relative w-20 h-24 sm:w-24 sm:h-28 rounded-lg overflow-hidden border border-amber-400/30 bg-slate-900/50 shadow-lg">
                                            @if ($anggota->foto)
                                                <img src="{{ asset('storage/' . $anggota->foto) }}" class="w-full h-full object-cover" alt="Foto Profil">
                                            @else
                                                {{-- Placeholder Photo --}}
                                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-b from-slate-800 to-slate-950 text-slate-400">
                                                    <span class="text-xl font-bold">{{ $anggota->inisial }}</span>
                                                    <span class="text-[7px] mt-1 opacity-60">BEM STMIK-DPR</span>
                                                </div>
                                            @endif
                                            
                                            {{-- Active Status Indicator Badge on card --}}
                                            <div class="absolute bottom-1 right-1 flex items-center gap-1 rounded bg-success-500/90 px-1 py-0.5 text-[6px] font-extrabold tracking-widest text-white uppercase shadow-sm">
                                                <span class="h-1 w-1 rounded-full bg-white animate-pulse"></span>
                                                AKTIF
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Details --}}
                                    <div class="flex-1 flex flex-col justify-center min-w-0 text-white leading-tight">
                                        <div class="mb-1.5">
                                            <p class="text-[8px] font-medium tracking-wider text-slate-400 uppercase">NAMA ANGGOTA</p>
                                            <h5 class="text-xs sm:text-sm font-bold truncate text-white uppercase">{{ $anggota->nama }}</h5>
                                        </div>
                                        
                                        <div class="mb-1.5">
                                            <p class="text-[8px] font-medium tracking-wider text-slate-400 uppercase">NIM</p>
                                            <p class="text-xs font-mono font-bold tracking-widest text-amber-300">{{ $anggota->nim }}</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 mb-1">
                                            <div>
                                                <p class="text-[7px] font-medium text-slate-400 uppercase">JABATAN</p>
                                                <p class="text-[10px] font-bold text-slate-200 truncate">
                                                    {{ $activeKeanggotaan?->jabatan?->nama ?? '-' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[7px] font-medium text-slate-400 uppercase">DEPARTEMEN</p>
                                                <p class="text-[10px] font-bold text-slate-200 truncate">
                                                    {{ $activeKeanggotaan?->departemen?->nama ?? 'Pengurus Inti' }}
                                                </p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 pt-1 border-t border-white/5">
                                            <div>
                                                <p class="text-[7px] font-medium text-slate-400 uppercase">PRODI</p>
                                                <p class="text-[9px] font-semibold text-slate-300 truncate">
                                                    {{ $anggota->prodi ?? '-' }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-[7px] font-medium text-slate-400 uppercase">ANGKATAN</p>
                                                <p class="text-[9px] font-semibold text-slate-300">
                                                    {{ $anggota->angkatan ?? '-' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- BACK SIDE --}}
                            <div class="absolute inset-0 w-full h-full rounded-2xl overflow-hidden backface-hidden shadow-2xl border border-amber-400/20 rotate-y-180"
                                style="background: linear-gradient(135deg, oklch(0.282 0.091 267.935), oklch(0.208 0.042 265.755));">
                                {{-- Card Accent Grid & Glow --}}
                                <div class="absolute inset-0 opacity-5 bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:16px_16px]"></div>
                                <div class="absolute -left-16 -top-16 w-40 h-40 rounded-full bg-indigo-500/10 blur-2xl"></div>
                                
                                <div class="p-4 flex flex-col justify-between h-full text-white">
                                    {{-- Top --}}
                                    <div class="text-center pb-2 border-b border-white/10">
                                        <h4 class="text-[10px] font-bold tracking-widest text-amber-400 uppercase">KETENTUAN PENGGUNAAN</h4>
                                    </div>

                                    {{-- Rules --}}
                                    <div class="my-2 text-[8px] text-slate-300 space-y-1 pl-3 list-decimal">
                                        <p>1. Kartu ini milik Badan Eksekutif Mahasiswa STMIK Dharmapala Riau.</p>
                                        <p>2. Kartu wajib digunakan dalam setiap agenda resmi organisasi BEM.</p>
                                        <p>3. Penyalahgunaan kartu ini dapat dikenakan sanksi tata tertib organisasi.</p>
                                        <p>4. Jika menemukan kartu ini, harap hubungi Sekretariat BEM STMIK-DPR.</p>
                                    </div>

                                    {{-- Signature & QR Code Area --}}
                                    <div class="flex items-end justify-between pt-2 border-t border-white/10">
                                        {{-- Info Sekretariat --}}
                                        <div class="text-left leading-tight">
                                            <p class="text-[8px] font-bold text-amber-400 uppercase tracking-wider">Sekretariat BEM</p>
                                            <p class="text-[6px] text-slate-300">STMIK Dharmapala Riau</p>
                                            <p class="text-[6px] text-slate-400">Pekanbaru, Riau, Indonesia</p>
                                        </div>

                                        {{-- Signature block --}}
                                        <div class="text-right leading-none relative">
                                            <p class="text-[7px] text-slate-400 mb-0.5">Pekanbaru, {{ \Carbon\Carbon::now()->format('d M Y') }}</p>
                                            <p class="text-[8px] font-bold text-slate-300">Pengurus BEM,</p>
                                            <p class="text-[8px] font-semibold text-slate-300">Presiden Mahasiswa</p>
                                            
                                            {{-- Dynamic stamp and signature overlay --}}
                                            <div class="h-10 my-0.5 flex justify-end items-center relative pr-4">
                                                {{-- Stamp Mockup (translucent circle stamp) --}}
                                                <div class="absolute right-6 -bottom-1 h-10 w-10 rounded-full border-2 border-dashed border-cyan-500/30 flex items-center justify-center rotate-12 pointer-events-none">
                                                    <span class="text-[5px] font-black text-cyan-500/30 uppercase tracking-tighter">BEM STMIK-DPR</span>
                                                </div>
                                                {{-- Stylized Signature Mockup --}}
                                                <svg class="h-8 w-16 text-amber-400/60" viewBox="0 0 100 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M10 25c15-15 35 15 45-5s20-25 35-5M25 15c5 5 15-15 10 20M55 35c5-10 15 10 10-10" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </div>

                                            <p class="text-[8px] font-bold text-amber-400 underline decoration-amber-400/40">
                                                {{ $presiden ? $presiden->nama : 'TENGKU ZAINUL' }}
                                            </p>
                                            <p class="text-[6px] text-slate-400 font-mono">NIM. {{ $presiden ? $presiden->nim : '210402071' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col sm:flex-row gap-3 w-full max-w-md pt-4 border-t border-gray-100 dark:border-gray-800">
                        <a href="{{ route('profile.print') }}" target="_blank"
                            class="flex-1 flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-amber-500 to-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:from-amber-600 hover:to-amber-700 transition focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <x-heroicon-o-printer class="h-5 w-5" />
                            Cetak Kartu Anggota
                        </a>
                        
                        <a href="{{ route('profile.verify', $anggota->nim) }}" target="_blank"
                            class="flex items-center justify-center gap-2 rounded-lg bg-gray-100 hover:bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300">
                            <x-heroicon-o-check-badge class="h-5 w-5" />
                            Halaman Verifikasi
                        </a>
                    </div>
                </div>
            @else
                {{-- User is not linked to any BEM Member profile --}}
                <div class="glass-card p-8 text-center flex flex-col items-center justify-center h-full min-h-[300px]">
                    <div class="h-16 w-16 rounded-full bg-warning-100 dark:bg-warning-950/30 flex items-center justify-center text-warning-600 dark:text-warning-400 mb-4 shadow-inner">
                        <x-heroicon-o-exclamation-triangle class="h-8 w-8" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Akun Belum Terhubung</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mb-6 leading-relaxed">
                        Akun pengguna Anda saat ini belum dikaitkan dengan profil **Anggota BEM**. 
                        Fitur pembuatan dan pencetakan Kartu Tanda Anggota (KTA) hanya tersedia bagi akun pengurus BEM yang sah.
                    </p>
                    
                    @if (auth()->user()->isAdmin())
                        <div class="text-xs text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-200 dark:border-gray-800 max-w-md">
                            <span class="font-bold text-amber-500">Petunjuk Admin:</span> Anda dapat menghubungkan akun ini dengan data Anggota melalui menu 
                            <a href="{{ route('users.index') }}" class="text-primary-600 dark:text-primary-400 font-semibold underline hover:text-primary-700">Manajemen Pengguna</a> 
                            dengan mengedit detail pengguna dan memilih data anggota yang sesuai.
                        </div>
                    @else
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            Harap hubungi Administrator atau Pengurus BEM untuk menautkan akun Anda dengan profil Anggota BEM.
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* CSS Utilities untuk 3D Card Flip */
        .perspective-1000 {
            perspective: 1000px;
        }
        .transform-style-3d {
            transform-style: preserve-3d;
        }
        .backface-hidden {
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }
        .rotate-y-180 {
            transform: rotateY(180deg);
        }
    </style>
@endpush
