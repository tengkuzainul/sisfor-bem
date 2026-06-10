<div x-show="meta.total > 0" x-cloak class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-5 py-3.5 dark:border-gray-800 sm:flex-row">
    <p class="text-xs text-gray-500 dark:text-gray-400">
        Menampilkan <span class="font-medium" x-text="meta.from || 0"></span> –
        <span class="font-medium" x-text="meta.to || 0"></span> dari
        <span class="font-medium" x-text="meta.total || 0"></span> data
    </p>
    <div class="flex items-center gap-1" x-show="meta.last_page > 1">
        <button @click="prevPage()" :disabled="meta.current_page <= 1"
            class="rounded-lg p-1.5 text-gray-500 transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40 dark:text-gray-400 dark:hover:bg-gray-800">
            <x-heroicon-s-chevron-left class="h-4 w-4" />
        </button>
        <template x-for="page in pages" :key="'p'+page">
            <button x-text="page" @click="goToPage(page)"
                :class="page === meta.current_page ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'"
                :disabled="typeof page !== 'number'"
                class="min-w-[2rem] rounded-lg px-2.5 py-1.5 text-xs font-medium transition">
            </button>
        </template>
        <button @click="nextPage()" :disabled="meta.current_page >= meta.last_page"
            class="rounded-lg p-1.5 text-gray-500 transition hover:bg-gray-100 disabled:cursor-not-allowed disabled:opacity-40 dark:text-gray-400 dark:hover:bg-gray-800">
            <x-heroicon-s-chevron-right class="h-4 w-4" />
        </button>
    </div>
</div>
