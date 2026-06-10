{{-- Form partial for Program Kerja create/edit --}}
<div class="space-y-6">
    {{-- Section: Informasi Umum --}}
    <div>
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Informasi Umum</h3>

        @if($activeKepengurusan)
            <input type="hidden" name="kepengurusan_id" value="{{ $activeKepengurusan->id }}">
        @endif

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- Nama --}}
            <div class="sm:col-span-2">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Program Kerja <span class="text-danger-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $programKerja->nama ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nama') border-danger-400 @enderror"
                       placeholder="e.g. Seminar Nasional IT">
                @error('nama') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- Kategori --}}
            <div>
                <label for="kategori_proker_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                <select name="kategori_proker_id" id="kategori_proker_id"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_proker_id', $programKerja->kategori_proker_id ?? '') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Departemen --}}
            <div>
                <label for="departemen_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departemen</label>
                <select name="departemen_id" id="departemen_id"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                    <option value="">-- BPH (Tanpa Departemen) --</option>
                    @foreach($departemenList as $dept)
                        <option value="{{ $dept->id }}" {{ old('departemen_id', $programKerja->departemen_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Lokasi --}}
            <div>
                <label for="lokasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $programKerja->lokasi ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                       placeholder="e.g. Aula Kampus">
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-danger-500">*</span></label>
                <select name="status" id="status"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('status') border-danger-400 @enderror">
                    <option value="coming_soon" {{ old('status', $programKerja->status ?? 'coming_soon') === 'coming_soon' ? 'selected' : '' }}>Coming Soon</option>
                    <option value="berlangsung" {{ old('status', $programKerja->status ?? '') === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                    <option value="pending" {{ old('status', $programKerja->status ?? '') === 'pending' ? 'selected' : '' }}>Pending / Undur</option>
                    <option value="selesai" {{ old('status', $programKerja->status ?? '') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="sm:col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="3"
                          class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('deskripsi', $programKerja->deskripsi ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Section: Jadwal --}}
    <div class="border-t border-gray-100 pt-6 dark:border-gray-800">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jadwal</h3>
        <div class="grid gap-5 sm:grid-cols-2">
            {{-- Tanggal Mulai --}}
            <div>
                <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                       value="{{ old('tanggal_mulai', isset($programKerja) && $programKerja->tanggal_mulai ? $programKerja->tanggal_mulai->format('Y-m-d') : '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
            </div>

            {{-- Tanggal Selesai --}}
            <div>
                <label for="tanggal_selesai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                       value="{{ old('tanggal_selesai', isset($programKerja) && $programKerja->tanggal_selesai ? $programKerja->tanggal_selesai->format('Y-m-d') : '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                @error('tanggal_selesai') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- Catatan --}}
            <div class="sm:col-span-2">
                <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                <textarea name="catatan" id="catatan" rows="2"
                          class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                          placeholder="Catatan tambahan...">{{ old('catatan', $programKerja->catatan ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Section: Dokumentasi --}}
    <div class="border-t border-gray-100 pt-6 dark:border-gray-800">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Dokumentasi Kegiatan</h3>

        {{-- Existing dokumentasi (edit mode) --}}
        @if(isset($programKerja) && $programKerja->dokumentasi->count() > 0)
            <div class="mb-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach($programKerja->dokumentasi as $doc)
                    <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                        <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->judul ?? 'Dokumentasi' }}" class="h-28 w-full object-cover">
                        <label class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition group-hover:opacity-100 cursor-pointer">
                            <input type="checkbox" name="hapus_dokumentasi[]" value="{{ $doc->id }}" class="rounded border-white text-danger-600">
                            <span class="ml-2 text-xs text-white">Hapus</span>
                        </label>
                    </div>
                @endforeach
            </div>
        @endif

        <div>
            <label for="dokumentasi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Foto Dokumentasi</label>
            <input type="file" name="dokumentasi[]" id="dokumentasi" multiple accept="image/*"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-700 hover:file:bg-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Format: JPG, PNG, WEBP. Maks 5MB per file. Bisa pilih beberapa file.</p>
            @error('dokumentasi.*') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
