@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('proposal.index') }}"
        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Proposal</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Ajukan</span>
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Ajukan Proposal Kegiatan</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload proposal PDF sesuai dengan program kerja yang
            tersedia.</p>
    </div>
@endsection

@section('content')
    <div class="mx-auto max-w-2xl">
        <form action="{{ route('proposal.store') }}" method="POST" enctype="multipart/form-data" class="glass-card p-6">
            @csrf

            <div class="space-y-5">
                {{-- Program Kerja --}}
                <div>
                    <label for="program_kerja_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program
                        Kerja <span class="text-danger-500">*</span></label>
                    <select name="program_kerja_id" id="program_kerja_id" required
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('program_kerja_id') border-danger-400 @enderror">
                        <option value="">-- Pilih Program Kerja --</option>
                        @foreach ($programKerjaList as $pk)
                            <option value="{{ $pk->id }}" {{ old('program_kerja_id') == $pk->id ? 'selected' : '' }}>
                                {{ $pk->nama }}</option>
                        @endforeach
                    </select>
                    @error('program_kerja_id')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Proposal
                        <span class="text-danger-500">*</span></label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('judul') border-danger-400 @enderror"
                        placeholder="e.g. Proposal Seminar Nasional IT 2026">
                    @error('judul')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File Proposal PDF --}}
                <div>
                    <label for="file_proposal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">File
                        Proposal (PDF) <span class="text-danger-500">*</span></label>
                    <input type="file" name="file_proposal" id="file_proposal" accept=".pdf" required
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-700 hover:file:bg-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400 @error('file_proposal') border-danger-400 @enderror">
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Format PDF, maksimal 10MB.</p>
                    @error('file_proposal')
                        <p class="mt-1 text-xs text-danger-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Catatan Pengaju --}}
                <div>
                    <label for="catatan_pengaju"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                    <textarea name="catatan_pengaju" id="catatan_pengaju" rows="3"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                        placeholder="Catatan tambahan untuk reviewer...">{{ old('catatan_pengaju') }}</textarea>
                </div>
            </div>

            {{-- Flow Info --}}
            <div class="mt-6 rounded-lg bg-info-50 p-4 dark:bg-info-950/20">
                <h4 class="text-sm font-semibold text-info-700 dark:text-info-400">Alur Persetujuan</h4>
                <div class="mt-2 flex items-center gap-2 text-xs text-info-600 dark:text-info-400">
                    <span class="rounded bg-info-100 px-2 py-0.5 font-medium dark:bg-info-900/30">1. Pengurus</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                    <span class="rounded bg-info-100 px-2 py-0.5 font-medium dark:bg-info-900/30">2. Pembina</span>
                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                    <span
                        class="rounded bg-success-100 px-2 py-0.5 font-medium text-success-700 dark:bg-success-900/30 dark:text-success-400">Selesai</span>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                <a href="{{ route('proposal.index') }}"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Batal</a>
                <button type="submit"
                    class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">Ajukan
                    Proposal</button>
            </div>
        </form>
    </div>
@endsection
