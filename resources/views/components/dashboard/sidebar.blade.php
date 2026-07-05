{{-- Sidebar Component --}}
{{-- Usage: @include('components.dashboard.sidebar') --}}

@php
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | RBAC Menu Configuration
    |--------------------------------------------------------------------------
    | Each section has 'label', optional 'roles' (empty = all), and 'items'.
    | Each item has: label, icon, route, routeIs (active match), roles.
    | If roles is empty/null = all authenticated users can see it.
    | Sections are auto-hidden when no visible items exist.
    |--------------------------------------------------------------------------
    */
    $menu = [
        [
            'label' => 'Menu Utama',
            'items' => [
                [
                    'label' => 'Dashboard',
                    'icon' => 'heroicon-o-squares-2x2',
                    'route' => 'dashboard',
                    'routeIs' => 'dashboard',
                    'roles' => [], // semua role
                ],
            ],
        ],
        [
            'label' => 'Organisasi',
            'roles' => ['administrator', 'pembina'],
            'items' => [
                [
                    'label' => 'Kepengurusan',
                    'icon' => 'heroicon-o-shield-check',
                    'route' => 'kepengurusan.index',
                    'routeIs' => 'kepengurusan.*',
                    'roles' => ['administrator', 'pembina'],
                ],
                [
                    'label' => 'Anggota',
                    'icon' => 'heroicon-o-users',
                    'route' => 'anggota.index',
                    'routeIs' => 'anggota.*',
                    'roles' => ['administrator', 'pembina'],
                ],
                [
                    'label' => 'Departemen',
                    'icon' => 'heroicon-o-building-office',
                    'route' => 'departemen.index',
                    'routeIs' => 'departemen.*',
                    'roles' => ['administrator', 'pembina'],
                ],
                [
                    'label' => 'Jabatan',
                    'icon' => 'heroicon-o-briefcase',
                    'route' => 'jabatan.index',
                    'routeIs' => 'jabatan.*',
                    'roles' => ['administrator', 'pembina'],
                ],
            ],
        ],
        [
            'label' => 'Program Kerja',
            'items' => [
                [
                    'label' => 'Kategori Proker',
                    'icon' => 'heroicon-o-tag',
                    'route' => 'kategori-proker.index',
                    'routeIs' => 'kategori-proker.*',
                    'roles' => ['administrator', 'pembina'],
                ],
                [
                    'label' => 'Program Kerja',
                    'icon' => 'heroicon-o-calendar-days',
                    'route' => 'program-kerja.index',
                    'routeIs' => 'program-kerja.*',
                    'roles' => [], // semua role
                ],
            ],
        ],
        [
            'label' => 'Proposal',
            'items' => [
                [
                    'label' => 'Proposal Kegiatan',
                    'icon' => 'heroicon-o-document-text',
                    'route' => 'proposal.index',
                    'routeIs' => 'proposal.*',
                    'roles' => [], // semua role
                ],
            ],
        ],
        [
            'label' => 'Rekrutmen',
            'items' => [
                [
                    'label' => 'Data Rekrutmen',
                    'icon' => 'heroicon-o-megaphone',
                    'route' => 'rekrutmen.index',
                    'routeIs' => 'rekrutmen.*',
                    'roles' => ['administrator', 'pembina'],
                ],
                [
                    'label' => 'Pendaftar',
                    'icon' => 'heroicon-o-user-plus',
                    'route' => 'pendaftar.index',
                    'routeIs' => 'pendaftar.*',
                    'roles' => [], // semua role
                ],
            ],
        ],
        [
            'label' => 'Sistem',
            'roles' => ['administrator'],
            'items' => [
                [
                    'label' => 'Pengguna',
                    'icon' => 'heroicon-o-user-group',
                    'route' => 'users.index',
                    'routeIs' => 'users.*',
                    'roles' => ['administrator'],
                ],
            ],
        ],
    ];
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="glass-panel fixed top-3 bottom-3 left-3 z-40 flex w-[15.5rem] flex-col rounded-2xl transition-transform duration-300 ease-in-out lg:translate-x-0"
    @click.outside="if (window.innerWidth < 1024) sidebarOpen = false">
    {{-- Logo Area --}}
    <div class="flex h-16 items-center gap-3 border-b border-gray-200/40 px-5 dark:border-white/[0.08]">
        <div
            class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-primary-500 to-primary-700 shadow-sm">
            <x-heroicon-s-building-library class="h-5 w-5 text-white" />
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-bold tracking-tight text-gray-900 dark:text-white">BEM SISFOR</span>
            <span class="text-[10px] font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Management
                System</span>
        </div>
        {{-- Close button mobile --}}
        <button @click="sidebarOpen = false"
            class="ml-auto rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 lg:hidden dark:hover:bg-gray-800">
            <x-heroicon-o-x-mark class="h-5 w-5" />
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4 scrollbar-thin">
        @foreach ($menu as $section)
            @php
                // Check if the section itself is restricted
                $sectionAllowed = empty($section['roles'] ?? []) || $user->hasRole($section['roles']);
                if (!$sectionAllowed) {
                    continue;
                }

                // Filter visible items for this section
                $visibleItems = collect($section['items'])->filter(function ($item) use ($user) {
                    return empty($item['roles']) || $user->hasRole($item['roles']);
                });
                if ($visibleItems->isEmpty()) {
                    continue;
                }
            @endphp

            {{-- Section Header --}}
            <p
                class="mb-2 {{ !$loop->first ? 'mt-6' : '' }} px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                {{ $section['label'] }}
            </p>

            @foreach ($visibleItems as $item)
                @php
                    $isActive = request()->routeIs($item['routeIs']);
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150
                          {{ $isActive ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-400' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-800/50 dark:hover:text-gray-200' }}">
                    <x-dynamic-component :component="$item['icon']"
                        class="h-5 w-5 flex-shrink-0 {{ $isActive ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" />
                    {{ $item['label'] }}
                    @if ($isActive)
                        <span class="ml-auto h-1.5 w-1.5 rounded-full bg-primary-500"></span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </nav>

    {{-- User Profile Footer --}}
    <div class="border-t border-gray-200/40 p-3 dark:border-white/[0.08]">
        <div x-data="{ open: false }" class="relative">
            <button @click.stop="open = !open"
                class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.06]">
                <div class="flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full text-xs font-bold text-white shadow-sm"
                    style="background: linear-gradient(135deg, {{ $user->role_color }}cc, {{ $user->role_color }})">
                    {{ substr($user->name ?? 'A', 0, 1) }}
                </div>
                <div class="flex-1 text-left">
                    <p class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ $user->name ?? 'Admin' }}
                    </p>
                    <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ $user->role_label }}</p>
                </div>
                <x-heroicon-o-ellipsis-vertical class="h-4 w-4 text-gray-400" />
            </button>

            {{-- Profile Dropdown --}}
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95" @click.outside="open = false"
                class="absolute bottom-full left-0 mb-2 w-full rounded-lg border border-gray-200 bg-white py-1 shadow-xl dark:border-gray-700 dark:bg-gray-800">
                <a href="{{ route('profile.show') }}"
                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                    <x-heroicon-o-user-circle class="h-4 w-4" /> Profil Saya
                </a>
                <hr class="my-1 border-gray-200 dark:border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-danger-600 hover:bg-danger-50 dark:text-danger-400 dark:hover:bg-danger-950/30">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4" /> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- Sidebar Overlay (Mobile) --}}
<div x-show="sidebarOpen" x-cloak x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-gray-900/50 backdrop-blur-sm lg:hidden">
</div>
