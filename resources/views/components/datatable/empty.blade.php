{{-- Empty state for datatable --}}
<div x-show="!loading && !error && data.length === 0" x-cloak class="px-6 py-12 text-center">
    <x-heroicon-o-inbox class="mx-auto h-10 w-10 text-gray-300 dark:text-gray-600" />
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada data ditemukan.</p>
</div>

<div x-show="error && !loading" x-cloak class="px-6 py-12 text-center">
    <x-heroicon-o-exclamation-triangle class="mx-auto h-10 w-10 text-danger-400" />
    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Gagal memuat data.</p>
    <button @click="fetchData()" class="mt-2 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">Coba lagi</button>
</div>
