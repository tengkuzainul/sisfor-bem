{{-- Topbar / Header Component --}}
{{-- Usage: @include('components.dashboard.topbar') --}}

<header class="glass-panel fixed top-3 right-3 left-3 z-20 mx-0 flex h-14 items-center rounded-2xl px-4 sm:px-5 lg:left-[17.5rem]">
    {{-- Left: Hamburger + Breadcrumb --}}
    <div class="flex items-center gap-3">
        {{-- Mobile menu toggle --}}
        <button @click="sidebarOpen = !sidebarOpen"
                class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 lg:hidden dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <x-heroicon-o-bars-3 class="h-5 w-5" />
        </button>

        {{-- Breadcrumb --}}
        <nav class="hidden items-center gap-1 text-sm sm:flex">
            <a href="{{ route('dashboard') }}" class="text-gray-400 transition hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
                <x-heroicon-s-home class="h-4 w-4" />
            </a>
            @hasSection('breadcrumb')
                <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
                @yield('breadcrumb')
            @endif
        </nav>
    </div>

    {{-- Right: Actions --}}
    <div class="ml-auto flex items-center gap-1 sm:gap-2">

        {{-- Search Toggle --}}
        <div x-data="{ searchOpen: false }" class="relative">
            <button @click="searchOpen = !searchOpen"
                    class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                <x-heroicon-o-magnifying-glass class="h-5 w-5" />
            </button>

            {{-- Search Dropdown --}}
            <div x-show="searchOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 @click.outside="searchOpen = false"
                 x-trap.noscroll="searchOpen"
                 class="absolute right-0 top-full mt-2 w-72 rounded-xl border border-gray-200 bg-white p-3 shadow-xl sm:w-96 dark:border-gray-700 dark:bg-gray-800">
                <div class="relative">
                    <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <input type="text" placeholder="Cari menu, anggota, kegiatan..."
                           class="w-full rounded-lg border border-gray-200 bg-gray-50 py-2.5 pl-10 pr-4 text-sm text-gray-700 placeholder-gray-400 outline-none transition focus:border-primary-400 focus:bg-white focus:ring-2 focus:ring-primary-100 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-500 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                           autofocus>
                </div>
                <div class="mt-2 text-center text-xs text-gray-400 dark:text-gray-500">
                    Tekan <kbd class="rounded border border-gray-300 bg-gray-100 px-1.5 py-0.5 font-mono text-[10px] dark:border-gray-600 dark:bg-gray-700">ESC</kbd> untuk menutup
                </div>
            </div>
        </div>

        {{-- Dark Mode Toggle --}}
        <button x-data="{ dark: document.documentElement.classList.contains('dark') }"
                x-init="$watch('dark', val => {
                    localStorage.setItem('theme', val ? 'dark' : 'light');
                    document.documentElement.classList.toggle('dark', val);
                })"
                @click="dark = !dark"
                class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
            <x-heroicon-o-sun class="h-5 w-5 dark:hidden" />
            <x-heroicon-o-moon class="hidden h-5 w-5 dark:block" />
        </button>

        {{-- Notifications --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="relative rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                <x-heroicon-o-bell class="h-5 w-5" />
                {{-- Notification dot --}}
                <span class="absolute right-1.5 top-1.5 flex h-2 w-2">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-danger-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-danger-500"></span>
                </span>
            </button>

            {{-- Notification Dropdown --}}
            <div x-show="open"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 @click.outside="open = false"
                 class="absolute right-0 top-full mt-2 w-80 rounded-xl border border-gray-200 bg-white shadow-xl sm:w-96 dark:border-gray-700 dark:bg-gray-800">

                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifikasi</h3>
                    <button class="text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400">Tandai semua dibaca</button>
                </div>

                <div class="max-h-80 divide-y divide-gray-100 overflow-y-auto dark:divide-gray-700">
                    {{-- Notification Item --}}
                    <a href="#" class="flex gap-3 px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-info-100 dark:bg-info-900/50">
                            <x-heroicon-s-calendar class="h-4 w-4 text-info-600 dark:text-info-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Rapat Pleno Mingguan</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">Rapat akan dimulai besok pukul 19:00 WIB</p>
                            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">2 jam lalu</p>
                        </div>
                        <span class="mt-1 h-2 w-2 flex-shrink-0 rounded-full bg-primary-500"></span>
                    </a>

                    <a href="#" class="flex gap-3 px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-success-100 dark:bg-success-900/50">
                            <x-heroicon-s-user-plus class="h-4 w-4 text-success-600 dark:text-success-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Anggota Baru Terdaftar</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">5 anggota baru mendaftar hari ini</p>
                            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">5 jam lalu</p>
                        </div>
                    </a>

                    <a href="#" class="flex gap-3 px-4 py-3 transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-warning-100 dark:bg-warning-900/50">
                            <x-heroicon-s-exclamation-triangle class="h-4 w-4 text-warning-600 dark:text-warning-400" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Laporan Keuangan</p>
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">Laporan bulan Januari perlu disetujui</p>
                            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">1 hari lalu</p>
                        </div>
                    </a>
                </div>

                <div class="border-t border-gray-100 p-2 dark:border-gray-700">
                    <a href="#" class="block rounded-lg py-2 text-center text-xs font-medium text-primary-600 transition hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-950/30">
                        Lihat Semua Notifikasi
                    </a>
                </div>
            </div>
        </div>

        {{-- User Avatar (mobile) --}}
        <div x-data="{ open: false }" class="relative lg:hidden">
            <button @click="open = !open"
                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-xs font-bold text-white shadow-sm">
                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
            </button>
            <div x-show="open"
                 x-cloak
                 x-transition
                 @click.outside="open = false"
                 class="absolute right-0 top-full mt-2 w-48 rounded-xl border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                    <x-heroicon-o-user-circle class="h-4 w-4" /> Profil
                </a>
                <hr class="my-1 border-gray-100 dark:border-gray-700">
                <form method="POST" action="#">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-danger-600 hover:bg-danger-50 dark:text-danger-400 dark:hover:bg-danger-950/30">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4" /> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
