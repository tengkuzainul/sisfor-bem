{{-- Form partial for Kategori Proker create/edit --}}
<div class="space-y-5">
    {{-- Nama --}}
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kategori <span class="text-danger-500">*</span></label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $kategoriProker->nama ?? '') }}"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nama') border-danger-400 @enderror"
               placeholder="e.g. Seminar, Workshop, Lomba">
        @error('nama') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Warna --}}
    <div>
        <label for="warna" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Warna <span class="text-danger-500">*</span></label>
        <div class="mt-1.5 flex items-center gap-3">
            <input type="color" name="warna" id="warna" value="{{ old('warna', $kategoriProker->warna ?? '#3b82f6') }}"
                   class="h-10 w-14 cursor-pointer rounded-lg border border-gray-200 bg-white p-1 dark:border-gray-700 dark:bg-gray-800">
            <input type="text" id="warna_text" value="{{ old('warna', $kategoriProker->warna ?? '#3b82f6') }}" readonly
                   class="w-24 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-sm text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
        </div>
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Warna ini akan digunakan pada tampilan kalender.</p>
        @error('warna') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="3"
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('deskripsi', $kategoriProker->deskripsi ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('warna').addEventListener('input', function() {
    document.getElementById('warna_text').value = this.value;
});
</script>
@endpush
