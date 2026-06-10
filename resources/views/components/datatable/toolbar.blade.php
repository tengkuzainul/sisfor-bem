@props(['placeholder' => 'Cari data...'])

<div class="flex flex-col gap-3 border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:flex-row sm:items-center sm:justify-between">
    <div class="relative">
        <x-heroicon-o-magnifying-glass class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
        <input type="text" x-model="search" @input.debounce.300ms="doSearch()" placeholder="{{ $placeholder }}"
            class="w-full rounded-lg border border-gray-200 bg-white/60 py-2 pl-9 pr-3 text-sm text-gray-700 placeholder-gray-400 transition focus:border-primary-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-200 dark:placeholder-gray-500 sm:w-64" />
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
