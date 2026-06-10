{{-- Form partial for Departemen create/edit --}}
<div class="space-y-5">
    {{-- Kepengurusan --}}
    <div>
        <label for="kepengurusan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kepengurusan <span class="text-danger-500">*</span></label>
        <select name="kepengurusan_id" id="kepengurusan_id"
                class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('kepengurusan_id') border-danger-400 @enderror">
            <option value="">-- Pilih Kepengurusan --</option>
            @foreach($kepengurusanList as $kep)
                <option value="{{ $kep->id }}" {{ old('kepengurusan_id', $departemen->kepengurusan_id ?? '') == $kep->id ? 'selected' : '' }}>
                    {{ $kep->nama }} {{ $kep->is_active ? '(Aktif)' : '' }}
                </option>
            @endforeach
        </select>
        @error('kepengurusan_id') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Nama --}}
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Departemen <span class="text-danger-500">*</span></label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $departemen->nama ?? '') }}"
               placeholder="e.g. Humas & Kominfo"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nama') border-danger-400 @enderror">
        @error('nama') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Singkatan --}}
    <div>
        <label for="singkatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Singkatan</label>
        <input type="text" name="singkatan" id="singkatan" value="{{ old('singkatan', $departemen->singkatan ?? '') }}"
               placeholder="e.g. KOMINFO"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="3"
                  placeholder="Deskripsi singkat departemen..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('deskripsi', $departemen->deskripsi ?? '') }}</textarea>
    </div>
</div>
