{{-- Form partial for Anggota create/edit --}}
<div class="space-y-6">
    {{-- Section: Foto Profil --}}
    <div>
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Foto Profil</h3>
        <div class="flex items-center gap-6">
            {{-- Preview --}}
            <div class="shrink-0">
                @if(isset($anggota) && $anggota->foto)
                    <img id="foto-preview" src="{{ asset('storage/' . $anggota->foto) }}" alt="Foto" class="h-24 w-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                @else
                    <div id="foto-preview-placeholder" class="flex h-24 w-24 items-center justify-center rounded-full bg-primary-50 text-2xl font-bold text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border-2 border-gray-200 dark:border-gray-700">
                        {{ isset($anggota) ? strtoupper(substr($anggota->nama, 0, 2)) : '?' }}
                    </div>
                    <img id="foto-preview" src="#" alt="Preview" class="hidden h-24 w-24 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                @endif
            </div>
            <div class="flex-1">
                <label for="foto" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       onchange="previewFoto(this)"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-primary-700 hover:file:bg-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Format: JPG, PNG, WEBP. Maks 2MB.</p>
                @error('foto') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                @if(isset($anggota) && $anggota->foto)
                    <label class="mt-2 inline-flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                        <input type="checkbox" name="hapus_foto" value="1" class="rounded border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                        Hapus foto saat ini
                    </label>
                @endif
            </div>
        </div>
    </div>

    {{-- Section: Data Pribadi --}}
    <div class="border-t border-gray-100 pt-6 dark:border-gray-800">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Data Pribadi</h3>
        <div class="grid gap-5 sm:grid-cols-2">
            {{-- Nama --}}
            <div class="sm:col-span-2">
                <label for="nama" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-danger-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $anggota->nama ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nama') border-danger-400 @enderror">
                @error('nama') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- NIM --}}
            <div>
                <label for="nim" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIM <span class="text-danger-500">*</span></label>
                <input type="text" name="nim" id="nim" value="{{ old('nim', $anggota->nim ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('nim') border-danger-400 @enderror">
                @error('nim') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $anggota->email ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
            </div>

            {{-- No HP --}}
            <div>
                <label for="no_hp" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP</label>
                <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp', $anggota->no_hp ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
            </div>

            {{-- Jenis Kelamin --}}
            <div>
                <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin <span class="text-danger-500">*</span></label>
                <select name="jenis_kelamin" id="jenis_kelamin"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin', $anggota->jenis_kelamin ?? '') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $anggota->jenis_kelamin ?? '') === 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- Angkatan --}}
            <div>
                <label for="angkatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Angkatan</label>
                <input type="text" name="angkatan" id="angkatan" value="{{ old('angkatan', $anggota->angkatan ?? '') }}" placeholder="e.g. 2023"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
            </div>

            {{-- Prodi --}}
            <div>
                <label for="prodi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Studi</label>
                <input type="text" name="prodi" id="prodi" value="{{ old('prodi', $anggota->prodi ?? '') }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
            </div>

            {{-- Alamat --}}
            <div class="sm:col-span-2">
                <label for="alamat" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                <textarea name="alamat" id="alamat" rows="2"
                          class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('alamat', $anggota->alamat ?? '') }}</textarea>
            </div>
        </div>
    </div>

    {{-- Section: Keanggotaan / Penempatan --}}
    <div class="border-t border-gray-100 pt-6 dark:border-gray-800">
        <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Penempatan Keanggotaan</h3>

        @if($activeKepengurusan)
            <input type="hidden" name="kepengurusan_id" value="{{ $activeKepengurusan->id }}">
        @endif

        <div class="grid gap-5 sm:grid-cols-2">
            {{-- Jabatan --}}
            <div>
                <label for="jabatan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jabatan <span class="text-danger-500">*</span></label>
                <select name="jabatan_id" id="jabatan_id"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('jabatan_id') border-danger-400 @enderror">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatanList as $jbt)
                        <option value="{{ $jbt->id }}" {{ old('jabatan_id', $keanggotaan->jabatan_id ?? '') == $jbt->id ? 'selected' : '' }}>{{ $jbt->nama }}</option>
                    @endforeach
                </select>
                @error('jabatan_id') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
            </div>

            {{-- Departemen --}}
            <div>
                <label for="departemen_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departemen</label>
                <select name="departemen_id" id="departemen_id"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                    <option value="">-- BPH (Tanpa Departemen) --</option>
                    @foreach($departemenList as $dept)
                        <option value="{{ $dept->id }}" {{ old('departemen_id', $keanggotaan->departemen_id ?? '') == $dept->id ? 'selected' : '' }}>{{ $dept->nama }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Kosongkan jika anggota merupakan BPH.</p>
            </div>

            {{-- Status --}}
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                <select name="status" id="status"
                        class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                    <option value="aktif" {{ old('status', $keanggotaan->status ?? 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status', $keanggotaan->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                </select>
            </div>

            {{-- Tanggal Bergabung --}}
            <div>
                <label for="tanggal_bergabung" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Bergabung</label>
                <input type="date" name="tanggal_bergabung" id="tanggal_bergabung"
                       value="{{ old('tanggal_bergabung', isset($keanggotaan) && $keanggotaan->tanggal_bergabung ? $keanggotaan->tanggal_bergabung->format('Y-m-d') : now()->format('Y-m-d')) }}"
                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('foto-preview');
            const placeholder = document.getElementById('foto-preview-placeholder');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (placeholder) placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
