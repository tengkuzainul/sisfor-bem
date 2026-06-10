@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pengguna</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Manajemen Pengguna</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola akun pengguna dan role akses sistem.</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700">
            <x-heroicon-o-plus class="h-4 w-4" />
            Tambah Pengguna
        </a>
    </div>
@endsection

@section('content')
    <div x-data="dataTable({ url: '{{ route('users.index') }}', sortBy: 'name' })" class="glass-card">
        <x-datatable.toolbar placeholder="Cari pengguna (nama, email, role)..." />

        <div class="relative overflow-x-auto">
            <x-datatable.loading />

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-12">#</th>
                        <th @click="sort('name')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Nama <x-datatable.sort-icon column="name" /></span>
                        </th>
                        <th @click="sort('email')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Email <x-datatable.sort-icon column="email" /></span>
                        </th>
                        <th @click="sort('role')" class="cursor-pointer select-none px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 transition hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <span class="flex items-center gap-1">Role <x-datatable.sort-icon column="role" /></span>
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Anggota Terkait</th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    <template x-for="(item, index) in data" :key="item.id">
                        <tr class="transition hover:bg-gray-50/50 dark:hover:bg-gray-800/30">
                            <td class="px-6 py-3.5 text-xs text-gray-400" x-text="meta.from + index"></td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-bold text-white"
                                         :style="'background-color:' + item.role_color"
                                         x-text="item.name.charAt(0).toUpperCase()"></div>
                                    <span class="font-medium text-gray-900 dark:text-white" x-text="item.name"></span>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400" x-text="item.email"></td>
                            <td class="px-6 py-3.5">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                      :style="'background-color:' + item.role_color + '22; color:' + item.role_color">
                                    <span class="h-1.5 w-1.5 rounded-full" :style="'background-color:' + item.role_color"></span>
                                    <span x-text="item.role_label"></span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 dark:text-gray-400">
                                <span x-text="item.anggota ? item.anggota.nama + ' (' + item.anggota.nim + ')' : '-'"></span>
                            </td>
                            <td class="px-6 py-3.5">
                                <div class="flex items-center gap-1">
                                    <a :href="'{{ route('users.index') }}/' + item.id + '/edit'"
                                       class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-primary-600 dark:hover:bg-gray-800 dark:hover:text-primary-400">
                                        <x-heroicon-o-pencil-square class="h-4 w-4" />
                                    </a>
                                    <button @click="destroy('{{ route('users.index') }}/' + item.id, 'Hapus pengguna ini?')"
                                            class="rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-danger-600 dark:hover:bg-gray-800 dark:hover:text-danger-400">
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
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
