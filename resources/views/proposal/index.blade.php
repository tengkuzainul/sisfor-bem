@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Proposal Kegiatan</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Proposal Kegiatan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                @if (auth()->user()->isPengurus())
                    Kelola pengajuan proposal kegiatan Anda.
                @elseif(auth()->user()->isPembina())
                    Review proposal kegiatan yang diajukan pengurus.
                @else
                    Seluruh proposal kegiatan dalam sistem.
                @endif
            </p>
        </div>
        @if (auth()->user()->isPengurus() || auth()->user()->isAdmin())
            <a href="{{ route('proposal.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700">
                <x-heroicon-o-plus class="h-4 w-4" />
                Ajukan Proposal
            </a>
        @endif
    </div>
@endsection

@section('content')
    <div x-data="dataTable({ url: '{{ route('proposal.index') }}', sortBy: 'created_at', sortDir: 'desc', statusFilter: '' })" class="glass-card">
        {{-- Toolbar --}}
        <div
            class="flex flex-col gap-3 border-b border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
            <div class="relative flex-1 sm:max-w-xs">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                <input type="text" x-model.debounce.400ms="search" @input="currentPage = 1; fetchData()"
                    class="w-full rounded-lg border border-gray-200 bg-white py-2 pl-10 pr-4 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                    placeholder="Cari proposal...">
            </div>
            <div class="flex items-center gap-2">
                <select x-model="statusFilter" @change="currentPage = 1; fetchData()"
                    class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                    <option value="">Semua Status</option>
                    <option value="diajukan">Diajukan</option>
                    <option value="review_pembina">Review Pembina</option>
                    <option value="revisi_pembina">Revisi (Pembina)</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-12">
                            #</th>
                        <th @click="sort('judul')"
                            class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Judul <x-datatable.sort-icon column="judul" /></span>
                        </th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Program Kerja</th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Pengaju</th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Status</th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Tahap</th>
                        <th @click="sort('created_at')"
                            class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Tanggal <x-datatable.sort-icon
                                    column="created_at" /></span>
                        </th>
                        <th
                            class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(item, index) in data" :key="item.id">
                        <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-3.5 text-xs text-gray-400" x-text="meta.from + index"></td>
                            <td class="px-6 py-3.5">
                                <p class="font-medium text-gray-900 dark:text-white" x-text="item.judul"></p>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400"
                                x-text="item.program_kerja?.nama || '-'"></td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400" x-text="item.pengaju?.name || '-'">
                            </td>
                            <td class="px-6 py-3.5">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    :style="'background-color:' + item.status_color + '22; color:' + item.status_color">
                                    <span class="h-1.5 w-1.5 rounded-full"
                                        :style="'background-color:' + item.status_color"></span>
                                    <span x-text="item.status_label"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400" x-text="item.step_label"></td>
                            <td class="px-6 py-3.5 text-xs text-gray-500 dark:text-gray-400"
                                x-text="new Date(item.created_at).toLocaleDateString('id-ID')"></td>
                            <td class="px-6 py-3.5">
                                <a :href="'{{ route('proposal.index') }}/' + item.id"
                                    class="inline-flex items-center gap-1 rounded-lg px-2.5 py-1.5 text-xs font-medium text-primary-600 transition hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-950/30">
                                    <x-heroicon-o-eye class="h-3.5 w-3.5" /> Detail
                                </a>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <x-datatable.empty />
        </div>

        <x-datatable.pagination />
    </div>
@endsection
