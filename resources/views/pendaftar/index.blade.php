@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pendaftar</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Data Pendaftar</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola dan review data pendaftar rekrutmen.</p>
        </div>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({
        url: '{{ route('pendaftar.index') }}',
        sortBy: 'created_at',
        sortDir: 'desc',
        filters: {
            status_filter: '{{ request('status_filter', '') }}',
            rekrutmen_id: '{{ request('rekrutmen_id', '') }}',
            departemen_id: '{{ request('departemen_id', '') }}'
        }
    })" class="glass-card">
        {{-- Toolbar --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 px-6 py-4 dark:border-gray-800">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div class="relative flex-1">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input type="text" x-model.debounce.400ms="search" placeholder="Cari nama, NIM, kode..."
                           class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-9 pr-4 text-sm outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 sm:max-w-xs">
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <select x-model="filters.rekrutmen_id" @change="fetchData()"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <option value="">Semua Rekrutmen</option>
                    @foreach($rekrutmenList as $r)
                        <option value="{{ $r->id }}">{{ $r->judul }}</option>
                    @endforeach
                </select>
                <select x-model="filters.departemen_id" @change="fetchData()"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <option value="">Semua Departemen</option>
                    @foreach($departemenList as $d)
                        <option value="{{ $d->id }}">{{ $d->nama }}</option>
                    @endforeach
                </select>
                <select x-model="filters.status_filter" @change="fetchData()"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Pendaftar::STATUSES as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th @click="sort('nama_lengkap')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Pendaftar <x-datatable.sort-icon column="nama_lengkap" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dept. Pilihan</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Review</th>
                        <th @click="sort('created_at')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Tanggal <x-datatable.sort-icon column="created_at" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(item, index) in data" :key="item.id">
                        <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-3.5 text-gray-500 dark:text-gray-400" x-text="meta.from + index"></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-xs font-bold text-primary-700 dark:bg-primary-900/50 dark:text-primary-400"
                                         x-text="item.nama_lengkap?.charAt(0)?.toUpperCase()"></div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white" x-text="item.nama_lengkap"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <span x-text="item.nim"></span> &bull; <span x-text="item.prodi"></span>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="space-y-1">
                                    <span class="inline-flex items-center rounded bg-primary-50 px-1.5 py-0.5 text-[10px] font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-400" x-text="'1: ' + (item.departemen_pilihan1?.nama || '-')"></span>
                                    <template x-if="item.departemen_pilihan2">
                                        <span class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400" x-text="'2: ' + item.departemen_pilihan2.nama"></span>
                                    </template>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-lg bg-accent-50 px-2 py-0.5 text-xs font-medium text-accent-700 dark:bg-accent-900/30 dark:text-accent-400">
                                    <x-heroicon-o-chat-bubble-left-right class="h-3 w-3" />
                                    <span x-text="item.reviews_count"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400" x-text="formatDate(item.created_at)"></td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold"
                                      :style="'background:' + item.status_color + '15; color:' + item.status_color">
                                    <span class="h-1.5 w-1.5 rounded-full" :style="'background:' + item.status_color"></span>
                                    <span x-text="item.status_label"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <a :href="'{{ url('pendaftar') }}/' + item.id"
                                   class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300" title="Detail & Review">
                                    <x-heroicon-o-eye class="h-4 w-4" />
                                </a>
                            </td>
                        </tr>
                    </template>

                    {{-- Empty --}}
                    <tr x-show="!loading && !error && data.length === 0" x-cloak>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <x-heroicon-o-user-group class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
                            <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">Belum ada pendaftar</h3>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Data pendaftar akan muncul setelah ada yang mendaftar.</p>
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
