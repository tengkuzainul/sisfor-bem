{{-- Form partial for Jabatan create/edit --}}
<div class="space-y-5">
    {{-- Nama --}}
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Jabatan <span class="text-danger-500">*</span></label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $jabatan->nama ?? '') }}"
               placeholder="e.g. Ketua Umum"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nama') border-danger-400 @enderror">
        @error('nama') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Level --}}
    <div>
        <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level Hierarki <span class="text-danger-500">*</span></label>
        <input type="number" name="level" id="level" value="{{ old('level', $jabatan->level ?? 0) }}" min="0"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('level') border-danger-400 @enderror">
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Semakin kecil angka, semakin tinggi posisinya (0 = tertinggi).</p>
        @error('level') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="3"
                  placeholder="Deskripsi singkat jabatan..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('deskripsi', $jabatan->deskripsi ?? '') }}</textarea>
    </div>
</div>
