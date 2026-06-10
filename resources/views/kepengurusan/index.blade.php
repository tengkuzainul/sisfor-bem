@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Kepengurusan</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Kepengurusan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola periode kepengurusan BEM Sistem Informasi.</p>
        </div>
        <a href="{{ route('kepengurusan.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <x-heroicon-o-plus class="h-4 w-4" />
            Tambah Kepengurusan
        </a>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({ url: '{{ route('kepengurusan.index') }}', sortBy: 'tanggal_mulai', sortDir: 'desc' })" class="glass-card">
        <x-datatable.toolbar placeholder="Cari kepengurusan..." />

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th @click="sort('nama')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Kepengurusan <x-datatable.sort-icon column="nama" /></span>
                        </th>
                        <th @click="sort('tanggal_mulai')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Masa Jabatan <x-datatable.sort-icon column="tanggal_mulai" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dept</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Anggota</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(item, index) in data" :key="item.id">
                        <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-3.5 text-gray-500 dark:text-gray-400" x-text="meta.from + index"></td>
                            <td class="px-6 py-3.5">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white" x-text="item.nama"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'Periode ' + item.periode"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="formatDate(item.tanggal_mulai) + ' – ' + formatDate(item.tanggal_selesai)"></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                    <x-heroicon-o-building-office class="h-3 w-3" />
                                    <span x-text="item.departemen_count"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-accent-50 px-2 py-0.5 text-xs font-medium text-accent-700 dark:bg-accent-900/30 dark:text-accent-400">
                                    <x-heroicon-o-users class="h-3 w-3" />
                                    <span x-text="item.keanggotaan_count"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <template x-if="item.is_active">
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-success-500/15 px-2.5 py-0.5 text-[11px] font-semibold text-success-700 ring-1 ring-success-500/20 dark:bg-success-400/10 dark:text-success-400 dark:ring-success-400/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-success-500 animate-pulse"></span>
                                        Aktif
                                    </span>
                                </template>
                                <template x-if="!item.is_active">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-100/80 px-2.5 py-0.5 text-[10px] font-semibold text-gray-500 dark:bg-gray-800/60 dark:text-gray-400">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Nonaktif
                                    </span>
                                </template>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-1">
                                    <button x-show="!item.is_active" x-cloak
                                        @click="patchAction('{{ url('kepengurusan') }}/' + item.id + '/activate', 'Aktifkan kepengurusan ini?')"
                                        class="rounded-lg p-1.5 text-success-500 transition hover:bg-success-50 dark:hover:bg-success-900/30" title="Aktifkan">
                                        <x-heroicon-o-check-circle class="h-4 w-4" />
                                    </button>
                                    <button x-show="item.is_active" x-cloak
                                        @click="patchAction('{{ url('kepengurusan') }}/' + item.id + '/deactivate', 'Nonaktifkan kepengurusan ini?')"
                                        class="rounded-lg p-1.5 text-warning-500 transition hover:bg-warning-50 dark:hover:bg-warning-900/30" title="Nonaktifkan">
                                        <x-heroicon-o-pause-circle class="h-4 w-4" />
                                    </button>
                                    <a :href="'{{ url('kepengurusan') }}/' + item.id"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300" title="Detail">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    <a :href="'{{ url('kepengurusan') }}/' + item.id + '/edit'"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300" title="Edit">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    <button x-show="!item.is_active" x-cloak
                                        @click="deleteItem('{{ url('kepengurusan') }}/' + item.id)"
                                        class="rounded-lg p-1.5 text-gray-400 transition hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400" title="Hapus">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty --}}
                    <tr x-show="!loading && !error && data.length === 0" x-cloak>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <x-heroicon-o-clipboard-document-list class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
                            <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Belum ada kepengurusan</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mulai dengan menambahkan periode kepengurusan baru.</p>
                            <a href="{{ route('kepengurusan.create') }}" class="mt-4 inline-flex items-center gap-1 rounded-lg bg-primary-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-primary-700">
                                <x-heroicon-o-plus class="h-4 w-4" />
                                Tambah Kepengurusan
                            </a>
                        </td>
                    </tr>

                    {{-- Error --}}
                    <tr x-show="error && !loading" x-cloak>
                        <td colspan="7" class="px-6 py-12 text-center">
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
