{{-- Form partial for Kepengurusan create/edit --}}
<div class="space-y-5">
    {{-- Nama --}}
    <div>
        <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Kepengurusan <span class="text-danger-500">*</span></label>
        <input type="text" name="nama" id="nama" value="{{ old('nama', $kepengurusan->nama ?? '') }}"
               placeholder="e.g. BEM SISFOR 2025/2026"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nama') border-danger-400 @enderror">
        @error('nama') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Periode --}}
    <div>
        <label for="periode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Periode <span class="text-danger-500">*</span></label>
        <input type="text" name="periode" id="periode" value="{{ old('periode', $kepengurusan->periode ?? '') }}"
               placeholder="e.g. 2025/2026"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('periode') border-danger-400 @enderror">
        @error('periode') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Tanggal --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai <span class="text-danger-500">*</span></label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                   value="{{ old('tanggal_mulai', isset($kepengurusan) ? $kepengurusan->tanggal_mulai->format('Y-m-d') : '') }}"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('tanggal_mulai') border-danger-400 @enderror">
            @error('tanggal_mulai') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai <span class="text-danger-500">*</span></label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                   value="{{ old('tanggal_selesai', isset($kepengurusan) ? $kepengurusan->tanggal_selesai->format('Y-m-d') : '') }}"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('tanggal_selesai') border-danger-400 @enderror">
            @error('tanggal_selesai') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Visi --}}
    <div>
        <label for="visi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Visi</label>
        <textarea name="visi" id="visi" rows="3"
                  placeholder="Visi kepengurusan..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('visi', $kepengurusan->visi ?? '') }}</textarea>
    </div>

    {{-- Misi --}}
    <div>
        <label for="misi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Misi</label>
        <textarea name="misi" id="misi" rows="4"
                  placeholder="Misi kepengurusan (pisahkan dengan baris baru)..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('misi', $kepengurusan->misi ?? '') }}</textarea>
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="3"
                  placeholder="Deskripsi singkat..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('deskripsi', $kepengurusan->deskripsi ?? '') }}</textarea>
    </div>
</div>
