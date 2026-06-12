@extends('layouts.app')

@section('breadcrumb')
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dashboard</span>
@endsection

@section('page-header')
    <div
        class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-primary-600 via-primary-500 to-accent-500 p-6 shadow-lg shadow-primary-500/10 dark:overflow-visible dark:rounded-none dark:bg-none dark:p-0 dark:shadow-none">
        {{-- Decorative elements (light mode only) --}}
        <div class="absolute -right-4 -top-4 h-28 w-28 rounded-full bg-white/10 dark:hidden"></div>
        <div class="absolute -bottom-6 right-28 h-24 w-24 rounded-full bg-white/5 dark:hidden"></div>
        <div class="absolute left-1/3 top-0 h-16 w-16 rounded-full bg-white/5 dark:hidden"></div>

        <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-white dark:text-white">
                    Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}! 👋
                </h1>
                <p class="mt-1 text-sm text-primary-100 dark:text-gray-400">
                    Berikut ringkasan aktivitas organisasi BEM Sistem Informasi hari ini.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span
                    class="inline-flex items-center gap-1.5 rounded-lg bg-white/15 px-3 py-1.5 text-xs font-medium text-white ring-1 ring-white/25 dark:bg-primary-950/40 dark:text-primary-400 dark:ring-primary-800/50">
                    <span class="h-1.5 w-1.5 rounded-full bg-white animate-pulse dark:bg-primary-500"></span>
                    Periode {{ $kepengurusan?->periode ?? '2025/2026' }}
                </span>
                <button
                    class="inline-flex items-center gap-2 rounded-lg border border-white/25 bg-white/10 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-white/20 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <x-heroicon-o-arrow-down-tray class="h-4 w-4" />
                    Export
                </button>
            </div>
        </div>
    </div>
@endsection

@section('content')
    {{-- ========================================
         STAT CARDS
         ======================================== --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Total Anggota --}}
        <div
            class="stat-card glass-card group relative overflow-hidden p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Anggota
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalAnggota) }}</p>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Total anggota aktif dalam kepengurusan saat
                        ini.</div>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-primary-100 text-primary-600 transition group-hover:scale-110 dark:bg-primary-950/50 dark:text-primary-400">
                    <x-heroicon-o-users class="h-6 w-6" />
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-primary-500 to-primary-300 opacity-60 transition-opacity group-hover:opacity-100 dark:opacity-0 dark:group-hover:opacity-100">
            </div>
        </div>

        {{-- Kegiatan Aktif --}}
        <div
            class="stat-card glass-card group relative overflow-hidden p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Kegiatan Aktif
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($kegiatanAktif) }}</p>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Program kerja dengan status berlangsung.
                    </div>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-success-100 text-success-600 transition group-hover:scale-110 dark:bg-success-950/50 dark:text-success-400">
                    <x-heroicon-o-calendar-days class="h-6 w-6" />
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-success-500 to-success-300 opacity-60 transition-opacity group-hover:opacity-100 dark:opacity-0 dark:group-hover:opacity-100">
            </div>
        </div>

        {{-- Program Kerja --}}
        <div
            class="stat-card glass-card group relative overflow-hidden p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Program Kerja
                    </p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProgramKerja) }}
                    </p>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Total program kerja pada kepengurusan aktif.
                    </div>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-warning-100 text-warning-600 transition group-hover:scale-110 dark:bg-warning-950/50 dark:text-warning-400">
                    <x-heroicon-o-clipboard-document-list class="h-6 w-6" />
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-warning-500 to-warning-300 opacity-60 transition-opacity group-hover:opacity-100 dark:opacity-0 dark:group-hover:opacity-100">
            </div>
        </div>

        {{-- Proposal Kegiatan --}}
        <div
            class="stat-card glass-card group relative overflow-hidden p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Proposal
                        Kegiatan</p>
                    <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalProposals) }}
                    </p>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Jumlah proposal kegiatan yang tercatat.</div>
                </div>
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-accent-100 text-accent-600 transition group-hover:scale-110 dark:bg-accent-950/50 dark:text-accent-400">
                    <x-heroicon-o-document-text class="h-6 w-6" />
                </div>
            </div>
            <div
                class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-accent-500 to-accent-300 opacity-60 transition-opacity group-hover:opacity-100 dark:opacity-0 dark:group-hover:opacity-100">
            </div>
        </div>
    </div>

    {{-- ========================================
         CHART + PROGRESS SECTION
         ======================================== --}}
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">
        {{-- Chart Area (placeholder) --}}
        <div class="glass-card col-span-1 p-6 xl:col-span-2">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Statistik Anggota</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pertumbuhan anggota per bulan</p>
                </div>
                <div x-data="{ tab: 'bulanan' }"
                    class="flex overflow-hidden rounded-lg border border-gray-200 bg-gray-50 text-xs dark:border-gray-700 dark:bg-gray-800">
                    <button @click="tab = 'mingguan'"
                        :class="tab === 'mingguan' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' :
                            'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-1.5 font-medium transition">Mingguan</button>
                    <button @click="tab = 'bulanan'"
                        :class="tab === 'bulanan' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' :
                            'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-1.5 font-medium transition">Bulanan</button>
                    <button @click="tab = 'tahunan'"
                        :class="tab === 'tahunan' ? 'bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' :
                            'text-gray-500 dark:text-gray-400'"
                        class="px-3 py-1.5 font-medium transition">Tahunan</button>
                </div>
            </div>

            {{-- Simulated Chart using bars --}}
            <div class="mt-6 flex h-48 items-end gap-2 sm:gap-3">
                @php
                    $values = $chartValues ?: [];
                    $labels = $chartLabels ?: [];
                    $max = $values ? max($values) : 1;
                @endphp
                @foreach ($values as $i => $val)
                    <div class="group flex flex-1 flex-col items-center gap-1">
                        <div class="relative w-full">
                            <div
                                class="absolute -top-6 left-1/2 -translate-x-1/2 transform rounded bg-gray-800 px-1.5 py-0.5 text-[10px] font-medium text-white opacity-0 transition group-hover:opacity-100 dark:bg-gray-200 dark:text-gray-800">
                                {{ $val }}
                            </div>
                            <div class="mx-auto w-full max-w-[2rem] rounded-t-md bg-gradient-to-t from-primary-600 to-primary-400 transition-all duration-500 group-hover:from-primary-700 group-hover:to-primary-500 dark:from-primary-500 dark:to-primary-300"
                                style="height: {{ ($max ? $val / $max : 0) * 100 }}%">
                            </div>
                        </div>
                        <span
                            class="text-[10px] font-medium text-gray-400 dark:text-gray-500">{{ $labels[$i] ?? '-' }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Proker Progress --}}
        <div class="glass-card p-6">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Progress Proker</h3>
                <a href="#"
                    class="text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Lihat
                    Semua</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse($progressItems as $proker)
                    <div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium text-gray-700 dark:text-gray-300">{{ $proker['name'] }}</span>
                            <span
                                class="text-xs font-semibold {{ $proker['textClass'] }}">{{ $proker['progress'] }}%</span>
                        </div>
                        <div class="mt-2 h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $proker['barClass'] }} transition-all duration-700 ease-out"
                                style="width: {{ $proker['progress'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500 dark:text-gray-400">Belum ada program kerja untuk ditampilkan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ========================================
         RECENT ACTIVITY + QUICK ACTIONS
         ======================================== --}}
    <div class="mt-6 grid grid-cols-1 gap-6 xl:grid-cols-3">

        {{-- Kegiatan Mendatang --}}
        <div class="glass-card col-span-1 xl:col-span-2">
            <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Kegiatan Mendatang</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jadwal kegiatan terdekat</p>
                </div>
                <a href="#"
                    class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                    Lihat Kalender
                </a>
            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($upcomingEvents as $event)
                    <div
                        class="flex items-start gap-4 px-6 py-4 transition hover:bg-primary-50/30 dark:hover:bg-gray-800/30">
                        {{-- Date badge --}}
                        <div
                            class="flex flex-col items-center rounded-lg border border-primary-100 bg-gradient-to-b from-primary-50/80 to-white px-3 py-2 text-center dark:border-gray-700 dark:bg-none dark:bg-gray-800">
                            <span class="text-[10px] font-semibold uppercase text-primary-400 dark:text-gray-500">
                                {{ explode(' ', $event['date'])[1] ?? '-' }}
                            </span>
                            <span class="text-lg font-bold leading-tight text-primary-700 dark:text-white">
                                {{ explode(' ', $event['date'])[0] ?? '-' }}
                            </span>
                        </div>

                        {{-- Event info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $event['title'] }}</h4>
                                <span class="rounded-full {{ $event['badgeClass'] }} px-2 py-0.5 text-[10px] font-medium">
                                    {{ $event['type'] }}
                                </span>
                            </div>
                            <div
                                class="mt-1.5 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                    {{ $event['time'] }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <x-heroicon-o-map-pin class="h-3.5 w-3.5" />
                                    {{ $event['location'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Action --}}
                        <button
                            class="rounded-lg border border-gray-200 p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:border-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-300">
                            <x-heroicon-o-ellipsis-horizontal class="h-4 w-4" />
                        </button>
                    </div>
                @empty
                    <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">Tidak ada kegiatan mendatang yang
                        terdaftar.</div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions + Recent Activity --}}
        <div class="space-y-6">
            {{-- Quick Actions --}}
            <div class="glass-card p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Aksi Cepat</h3>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <button
                        class="flex flex-col items-center gap-2 rounded-xl border border-primary-100 bg-primary-50/30 px-3 py-4 text-center transition hover:border-primary-200 hover:bg-primary-100/50 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-primary-800 dark:hover:bg-primary-950/30">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900/50">
                            <x-heroicon-o-user-plus class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Tambah Anggota</span>
                    </button>
                    <button
                        class="flex flex-col items-center gap-2 rounded-xl border border-success-100 bg-success-50/30 px-3 py-4 text-center transition hover:border-success-200 hover:bg-success-100/50 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-success-800 dark:hover:bg-success-950/30">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-success-100 dark:bg-success-900/50">
                            <x-heroicon-o-calendar-days class="h-5 w-5 text-success-600 dark:text-success-400" />
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Buat Kegiatan</span>
                    </button>
                    <button
                        class="flex flex-col items-center gap-2 rounded-xl border border-accent-100 bg-accent-50/30 px-3 py-4 text-center transition hover:border-accent-200 hover:bg-accent-100/50 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-accent-800 dark:hover:bg-accent-950/30">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-accent-100 dark:bg-accent-900/50">
                            <x-heroicon-o-banknotes class="h-5 w-5 text-accent-600 dark:text-accent-400" />
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Catat Keuangan</span>
                    </button>
                    <button
                        class="flex flex-col items-center gap-2 rounded-xl border border-warning-100 bg-warning-50/30 px-3 py-4 text-center transition hover:border-warning-200 hover:bg-warning-100/50 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800/50 dark:hover:border-warning-800 dark:hover:bg-warning-950/30">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-lg bg-warning-100 dark:bg-warning-900/50">
                            <x-heroicon-o-document-text class="h-5 w-5 text-warning-600 dark:text-warning-400" />
                        </div>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">Buat Surat</span>
                    </button>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="glass-card p-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
                    <a href="#"
                        class="text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Semua</a>
                </div>

                <div class="mt-4 space-y-0">
                    @forelse($recentActivities as $i => $act)
                        <div
                            class="flex items-start gap-3 {{ $i > 0 ? 'pt-3' : '' }} {{ $i < count($recentActivities) - 1 ? 'pb-3 border-b border-gray-100 dark:border-gray-800' : '' }}">
                            <div
                                class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full {{ $act['bgClass'] }}">
                                @switch($act['icon'])
                                    @case('user-plus')
                                        <x-heroicon-s-user-plus class="h-3.5 w-3.5 {{ $act['iconClass'] }}" />
                                    @break

                                    @case('pencil-square')
                                        <x-heroicon-s-pencil-square class="h-3.5 w-3.5 {{ $act['iconClass'] }}" />
                                    @break

                                    @case('plus-circle')
                                        <x-heroicon-s-plus-circle class="h-3.5 w-3.5 {{ $act['iconClass'] }}" />
                                    @break

                                    @case('document-text')
                                        <x-heroicon-s-document-text class="h-3.5 w-3.5 {{ $act['iconClass'] }}" />
                                    @break

                                    @case('building-office')
                                        <x-heroicon-s-building-office class="h-3.5 w-3.5 {{ $act['iconClass'] }}" />
                                    @break
                                @endswitch
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold">{{ $act['user'] }}</span> {{ $act['action'] }}
                                </p>
                                <p class="mt-0.5 text-[10px] text-gray-400 dark:text-gray-500">{{ $act['time'] }}</p>
                            </div>
                        </div>
                        @empty
                            <div class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas terbaru untuk
                                ditampilkan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================
         ANGGOTA PER DEPARTEMEN TABLE
         ======================================== --}}
        <div class="mt-6 glass-card">
            <div
                class="flex flex-col gap-3 border-b border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between dark:border-gray-800">
                <div>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Distribusi Anggota</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah anggota per departemen</p>
                </div>
            </div>

            <div class="p-6">
                <table id="member-table">
                    <thead>
                        <tr>
                            <th>Departemen</th>
                            <th>Ketua</th>
                            <th>Jumlah Anggota</th>
                            <th>Proker Selesai</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departemenStats as $dept)
                            <tr>
                                <td class="font-medium">{{ $dept['nama'] }}</td>
                                <td>{{ $dept['ketua'] }}</td>
                                <td>
                                    <span class="inline-flex items-center gap-1">
                                        <x-heroicon-s-users class="h-3.5 w-3.5 text-gray-400" />
                                        {{ $dept['jumlahAnggota'] }} orang
                                    </span>
                                </td>
                                <td>{{ $dept['prokerSelesai'] }}/{{ $dept['totalProker'] }}</td>
                                <td>
                                    @if ($dept['status'] === 'active')
                                        <span
                                            class="inline-flex items-center rounded-full bg-success-100 px-2.5 py-0.5 text-xs font-medium text-success-700 dark:bg-success-900/40 dark:text-success-400">
                                            <span class="mr-1 h-1.5 w-1.5 rounded-full bg-success-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                            <span class="mr-1 h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada
                                    departemen terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', async () => {
                const el = document.getElementById('member-table');
                if (!el) return;

                const DataTable = await loadDataTable();
                new DataTable('#member-table', {
                    searchable: true,
                    sortable: true,
                    perPage: 10,
                    perPageSelect: [5, 10, 25],
                    labels: {
                        placeholder: 'Cari departemen...',
                        noRows: 'Tidak ada data',
                        info: 'Menampilkan {start} - {end} dari {rows} data',
                        perPage: '{select} data per halaman',
                    },
                });
            });
        </script>
    @endpush
