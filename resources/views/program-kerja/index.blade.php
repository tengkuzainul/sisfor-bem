@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Program Kerja</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Timeline Program Kerja</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola program kerja
                @if($activeKepengurusan)
                    <span class="font-medium text-primary-600 dark:text-primary-400">{{ $activeKepengurusan->nama }}</span>.
                @else
                    organisasi.
                @endif
            </p>
        </div>
        <a href="{{ route('program-kerja.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700">
            <x-heroicon-o-plus class="h-4 w-4" />
            Tambah Program Kerja
        </a>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({ url: '{{ route('program-kerja.index') }}', sortBy: 'tanggal_mulai', sortDir: 'desc' })" class="glass-card">
        {{-- Custom toolbar with filter --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-2">
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input type="text" x-model="search" @input.debounce.300ms="doSearch()" placeholder="Cari program kerja..."
                        class="w-full rounded-lg border border-gray-200 bg-white/60 py-2 pl-9 pr-3 text-sm text-gray-700 placeholder-gray-400 transition focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-200 dark:placeholder-gray-500 sm:w-56" />
                </div>
                <select x-model="statusFilter" @change="meta.current_page = 1; fetchData()"
                    class="rounded-lg border border-gray-200 bg-white/60 px-3 py-2 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-300">
                    <option value="">Semua Status</option>
                    <option value="coming_soon">Coming Soon</option>
                    <option value="berlangsung">Berlangsung</option>
                    <option value="pending">Pending / Undur</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                <span>Tampilkan</span>
                <select x-model="perPage" @change="changePerPage()"
                    class="rounded-lg border border-gray-200 bg-white/60 px-2 py-1.5 text-sm focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-200">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span>data</span>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">#</th>
                        <th @click="sort('nama')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Nama <x-datatable.sort-icon column="nama" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kategori</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Departemen</th>
                        <th @click="sort('tanggal_mulai')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Tanggal <x-datatable.sort-icon column="tanggal_mulai" /></span>
                        </th>
                        <th @click="sort('status')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Status <x-datatable.sort-icon column="status" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dok</th>
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
                                    <p class="text-xs text-gray-400" x-text="item.lokasi || ''"></p>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <template x-if="item.kategori">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium"
                                          :style="'background-color:' + (item.kategori?.warna || '#6b7280') + '20; color:' + (item.kategori?.warna || '#6b7280')">
                                        <span class="h-1.5 w-1.5 rounded-full" :style="'background-color:' + (item.kategori?.warna || '#6b7280')"></span>
                                        <span x-text="item.kategori?.nama"></span>
                                    </span>
                                </template>
                                <template x-if="!item.kategori">
                                    <span class="text-gray-400">-</span>
                                </template>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400" x-text="item.departemen?.singkatan || item.departemen?.nama || 'BPH'"></td>
                            <td class="px-6 py-3.5 text-xs text-gray-600 dark:text-gray-400">
                                <span x-text="formatDate(item.tanggal_mulai)"></span>
                                <template x-if="item.tanggal_selesai && item.tanggal_selesai !== item.tanggal_mulai">
                                    <span> - <span x-text="formatDate(item.tanggal_selesai)"></span></span>
                                </template>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium"
                                      :class="{
                                          'bg-info-50 text-info-700 dark:bg-info-900/30 dark:text-info-400': item.status_color === 'info',
                                          'bg-warning-50 text-warning-700 dark:bg-warning-900/30 dark:text-warning-400': item.status_color === 'warning',
                                          'bg-danger-50 text-danger-700 dark:bg-danger-900/30 dark:text-danger-400': item.status_color === 'danger',
                                          'bg-success-50 text-success-700 dark:bg-success-900/30 dark:text-success-400': item.status_color === 'success',
                                      }">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                          :class="{
                                              'bg-info-500': item.status_color === 'info',
                                              'bg-warning-500': item.status_color === 'warning',
                                              'bg-danger-500': item.status_color === 'danger',
                                              'bg-success-500': item.status_color === 'success',
                                          }"></span>
                                    <span x-text="item.status_label"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <x-heroicon-o-camera class="h-3.5 w-3.5" />
                                    <span x-text="item.dokumentasi_count"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-1">
                                    <a :href="'{{ url('program-kerja') }}/' + item.id" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300" title="Detail">
                                        <x-heroicon-o-eye class="h-4 w-4" />
                                    </a>
                                    <a :href="'{{ url('program-kerja') }}/' + item.id + '/edit'" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    <button @click="deleteItem('{{ url('program-kerja') }}/' + item.id)" class="rounded-lg p-1.5 text-gray-400 transition hover:bg-danger-50 hover:text-danger-600 dark:hover:bg-danger-900/30 dark:hover:text-danger-400">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>

                    <tr x-show="!loading && !error && data.length === 0" x-cloak>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <x-heroicon-o-calendar-days class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data program kerja ditemukan.</p>
                        </td>
                    </tr>

                    <tr x-show="error && !loading" x-cloak>
                        <td colspan="8" class="px-6 py-12 text-center">
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
