@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kategori Program Kerja</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Kategori Program Kerja</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola kategori untuk program kerja organisasi.</p>
        </div>
        <a href="{{ route('kategori-proker.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700">
            <x-heroicon-o-plus class="h-4 w-4" />
            Tambah Kategori
        </a>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({ url: '{{ route('kategori-proker.index') }}', sortBy: 'nama' })" class="glass-card">
        <x-datatable.toolbar placeholder="Cari kategori..." />

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th @click="sort('nama')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Nama <x-datatable.sort-icon column="nama" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Warna</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Deskripsi</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jml Proker</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(item, index) in data" :key="item.id">
                        <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-3.5 text-gray-500 dark:text-gray-400" x-text="meta.from + index"></td>
                            <td class="px-6 py-3.5 font-medium text-gray-900 dark:text-white" x-text="item.nama"></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-2">
                                    <span class="h-5 w-5 rounded-full border border-gray-200 dark:border-gray-700" :style="'background-color:' + item.warna"></span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400" x-text="item.warna"></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-gray-500 dark:text-gray-400">
                                <span x-text="item.deskripsi ? item.deskripsi.substring(0, 50) + (item.deskripsi.length > 50 ? '...' : '') : '-'"></span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400" x-text="item.program_kerja_count"></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-1">
                                    <a :href="'{{ url('kategori-proker') }}/' + item.id + '/edit'" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    <button @click="deleteItem('{{ url('kategori-proker') }}/' + item.id)" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="!loading && !error && data.length === 0" x-cloak>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <x-heroicon-o-tag class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data kategori ditemukan.</p>
                        </td>
                    </tr>

                    <tr x-show="error && !loading" x-cloak>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <x-heroicon-o-exclamation-triangle class="mx-auto h-10 w-10 text-danger-400" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Gagal memuat data.</p>
                            <button @click="fetchData()" class="mt-2 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">Coba lagi</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <x-datatable.pagination />
    </div>
@endsection
