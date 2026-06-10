@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('program-kerja.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Program Kerja</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Edit</span>
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Edit Program Kerja</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui data program kerja.</p>
    </div>
@endsection

@section('content')
    <div class="mx-auto max-w-3xl">
        <form action="{{ route('program-kerja.update', $programKerja) }}" method="POST" enctype="multipart/form-data" class="glass-card p-6">
            @csrf @method('PUT')
            @include('program-kerja._form')

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-gray-100 pt-6 dark:border-gray-800">
                <a href="{{ route('program-kerja.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">Batal</a>
                <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">Perbarui</button>
            </div>
        </form>
    </div>
@endsection
