@extends('layouts.pendaftaran')

@section('content')
    <div class="mx-auto max-w-lg py-12 text-center">
        {{-- Success Icon --}}
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-success-100 dark:bg-success-900/30">
            <svg class="h-10 w-10 text-success-600 dark:text-success-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pendaftaran Berhasil!</h1>
        <p class="mt-2 text-gray-500 dark:text-gray-400">
            Terima kasih telah mendaftar pada <strong class="text-gray-700 dark:text-gray-300">{{ $rekrutmen->judul }}</strong>.
        </p>

        {{-- Registration Code --}}
        @if($kode)
            <div class="mt-6 rounded-xl border border-primary-200 bg-primary-50 p-6 dark:border-primary-800 dark:bg-primary-950/30">
                <p class="text-sm text-gray-500 dark:text-gray-400">Kode Pendaftaran Anda</p>
                <p class="mt-2 text-3xl font-bold tracking-widest text-primary-700 dark:text-primary-400">{{ $kode }}</p>
                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Simpan kode ini untuk referensi selanjutnya.</p>
            </div>
        @endif

        {{-- Info --}}
        <div class="mt-8 rounded-xl border border-gray-200 bg-white p-5 text-left dark:border-gray-800 dark:bg-gray-900">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Apa Selanjutnya?</h3>
            <ul class="mt-3 space-y-2">
                <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    Data pendaftaran anda akan direview oleh tim kami.
                </li>
                <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    Anda akan dihubungi melalui email atau WhatsApp.
                </li>
                <li class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-primary-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                    Pantau terus informasi melalui social media resmi BEM SISFOR.
                </li>
            </ul>
        </div>

        {{-- Actions --}}
        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('pendaftaran.index') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                ← Halaman Rekrutmen
            </a>
            <a href="{{ route('home') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-primary-700">
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endsection
