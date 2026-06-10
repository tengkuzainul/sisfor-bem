@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Rekrutmen</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Rekrutmen</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola periode rekrutmen anggota baru BEM.</p>
        </div>
        <a href="{{ route('rekrutmen.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
            <x-heroicon-o-plus class="h-4 w-4" />
            Buat Rekrutmen
        </a>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({
        url: '{{ route('rekrutmen.index') }}',
        sortBy: 'created_at',
        sortDir: 'desc',
        filters: { status_filter: '' }
    })" class="glass-card">
        {{-- Toolbar --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
            <div class="relative flex-1">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                <input type="text" x-model.debounce.400ms="search" placeholder="Cari rekrutmen..."
                       class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-9 pr-4 text-sm outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 sm:max-w-xs">
            </div>
            <div class="flex items-center gap-2">
                <select x-model="filters.status_filter" @change="fetchData()"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="dibuka">Dibuka</option>
                    <option value="ditutup">Ditutup</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th @click="sort('judul')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Rekrutmen <x-datatable.sort-icon column="judul" /></span>
                        </th>
                        <th @click="sort('tanggal_mulai')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Periode <x-datatable.sort-icon column="tanggal_mulai" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Pendaftar</th>
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
                                    <p class="font-medium text-gray-900 dark:text-white" x-text="item.judul"></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="item.kepengurusan?.nama ?? '-'"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400">
                                <span x-text="formatDate(item.tanggal_mulai) + ' – ' + formatDate(item.tanggal_berakhir)"></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-primary-50 px-2 py-0.5 text-xs font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                    <x-heroicon-o-users class="h-3 w-3" />
                                    <span x-text="item.pendaftar_count"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                      :class="{
                                          'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400': item.status === 'draft',
                                          'bg-success-100 text-success-700 dark:bg-success-900/30 dark:text-success-400': item.status === 'dibuka',
                                          'bg-warning-100 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400': item.status === 'ditutup',
                                          'bg-info-100 text-info-700 dark:bg-info-900/30 dark:text-info-400': item.status === 'selesai',
                                      }">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                          :class="{
                                              'bg-gray-400': item.status === 'draft',
                                              'bg-success-500 animate-pulse': item.status === 'dibuka',
                                              'bg-warning-500': item.status === 'ditutup',
                                              'bg-info-500': item.status === 'selesai',
                                          }"></span>
                                    <span x-text="item.status_label"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-1">
                                    {{-- View --}}
                                    <a :href="'{{ url('rekrutmen') }}/' + item.id"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300" title="Detail">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    {{-- Edit --}}
                                    <a :href="'{{ url('rekrutmen') }}/' + item.id + '/edit'"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300" title="Edit">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    {{-- Delete (only if no pendaftar) --}}
                                    <button x-show="item.pendaftar_count === 0" x-cloak
                                        @click="deleteItem('{{ url('rekrutmen') }}/' + item.id)"
                                        class="rounded-lg p-1.5 text-gray-400 transition hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400" title="Hapus">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty --}}
                    <tr x-show="!loading && !error && data.length === 0" x-cloak>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <x-heroicon-o-megaphone class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
                            <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Belum ada rekrutmen</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Mulai dengan membuat periode rekrutmen baru.</p>
                            <a href="{{ route('rekrutmen.create') }}" class="mt-4 inline-flex items-center gap-1 rounded-lg bg-primary-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-primary-700">
                                <x-heroicon-o-plus class="h-4 w-4" />
                                Buat Rekrutmen
                            </a>
                        </td>
                    </tr>

                    {{-- Error --}}
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
