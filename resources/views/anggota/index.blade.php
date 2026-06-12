@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Anggota</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Anggota</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola data anggota organisasi.
                @if ($activeKepengurusan)
                    <span
                        class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-700 dark:bg-success-900/30 dark:text-success-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-success-500"></span>
                        {{ $activeKepengurusan->nama }}
                    </span>
                @endif
            </p>
        </div>
        <a href="{{ route('anggota.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700">
            <x-heroicon-o-plus class="h-4 w-4" />
            Tambah Anggota
        </a>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({ url: '{{ route('anggota.index') }}', sortBy: 'nama' })" class="glass-card">
        <x-datatable.toolbar placeholder="Cari anggota (nama, NIM, email, prodi)..." />

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th @click="sort('nama')"
                            class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Anggota <x-datatable.sort-icon column="nama" /></span>
                        </th>
                        <th @click="sort('nim')"
                            class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">NIM <x-datatable.sort-icon column="nim" /></span>
                        </th>
                        <th @click="sort('prodi')"
                            class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Prodi <x-datatable.sort-icon column="prodi" /></span>
                        </th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Jabatan</th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Departemen</th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status</th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(item, index) in data" :key="item.id">
                        <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">

                                    <template x-if="item.foto">
                                        <img :src="'{{ asset('storage') }}/' + item.foto" alt="Foto anggota"
                                            class="h-9 w-9 shrink-0 rounded-full object-cover" loading="lazy" />
                                    </template>
                                    <template x-if="!item.foto">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary-50 text-xs font-bold text-primary-700 dark:bg-primary-900/30 dark:text-primary-400"
                                            x-text="item.inisial || '?'"></div>
                                    </template>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white" x-text="item.nama"></p>
                                        <p class="text-xs text-gray-400" x-text="item.email || '-'"></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-xs text-gray-600 dark:text-gray-400" x-text="item.nim">
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400" x-text="item.prodi || '-'"></td>
                            <td class="px-6 py-3.5">
                                <span class="text-gray-900 dark:text-white"
                                    x-text="item.active_keanggotaan?.jabatan?.nama || '-'"></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <template x-if="item.active_keanggotaan?.departemen">
                                    <span
                                        class="inline-flex items-center rounded-full bg-accent-50 px-2.5 py-0.5 text-xs font-medium text-accent-700 dark:bg-accent-900/30 dark:text-accent-400"
                                        x-text="item.active_keanggotaan.departemen.singkatan || item.active_keanggotaan.departemen.nama"></span>
                                </template>
                                <template x-if="item.active_keanggotaan && !item.active_keanggotaan.departemen">
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">BPH</span>
                                </template>
                                <template x-if="!item.active_keanggotaan">
                                    <span class="text-gray-400">-</span>
                                </template>
                            </td>
                            <td class="px-6 py-3.5">
                                <template x-if="item.active_keanggotaan?.status === 'aktif'">
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-medium text-success-600 dark:text-success-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success-500"></span> Aktif
                                    </span>
                                </template>
                                <template x-if="!item.active_keanggotaan || item.active_keanggotaan?.status !== 'aktif'">
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-300 dark:bg-gray-600"></span> Nonaktif
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-1">
                                    <a :href="'{{ url('anggota') }}/' + item.id + '/edit'"
                                        class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    <button @click="deleteItem('{{ url('anggota') }}/' + item.id)"
                                        class="rounded-lg p-1.5 text-gray-400 transition hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="!loading && !error && data.length === 0" x-cloak>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <x-heroicon-o-users class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data anggota ditemukan.</p>
                        </td>
                    </tr>

                    <tr x-show="error && !loading" x-cloak>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <x-heroicon-o-exclamation-triangle class="mx-auto h-10 w-10 text-danger-400" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Gagal memuat data.</p>
                            <button @click="fetchData()"
                                class="mt-2 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">Coba
                                lagi</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <x-datatable.pagination />
    </div>
@endsection
