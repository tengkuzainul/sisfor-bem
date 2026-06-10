{{-- Form partial for Rekrutmen create/edit --}}
<div class="space-y-5">
    {{-- Kepengurusan --}}
    <div>
        <label for="kepengurusan_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kepengurusan <span class="text-danger-500">*</span></label>
        <select name="kepengurusan_id" id="kepengurusan_id"
                class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('kepengurusan_id') border-danger-400 @enderror">
            <option value="">— Pilih Kepengurusan —</option>
            @foreach($kepengurusan as $kp)
                <option value="{{ $kp->id }}" {{ old('kepengurusan_id', $rekrutmen->kepengurusan_id ?? '') == $kp->id ? 'selected' : '' }}>
                    {{ $kp->nama }} ({{ $kp->periode }})
                </option>
            @endforeach
        </select>
        @error('kepengurusan_id') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Judul --}}
    <div>
        <label for="judul" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Rekrutmen <span class="text-danger-500">*</span></label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $rekrutmen->judul ?? '') }}"
               placeholder="e.g. Open Recruitment BEM SISFOR 2025/2026"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('judul') border-danger-400 @enderror">
        @error('judul') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Tanggal --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai <span class="text-danger-500">*</span></label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                   value="{{ old('tanggal_mulai', isset($rekrutmen) && $rekrutmen->tanggal_mulai ? $rekrutmen->tanggal_mulai->format('Y-m-d') : '') }}"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('tanggal_mulai') border-danger-400 @enderror">
            @error('tanggal_mulai') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="tanggal_berakhir" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Berakhir <span class="text-danger-500">*</span></label>
            <input type="date" name="tanggal_berakhir" id="tanggal_berakhir"
                   value="{{ old('tanggal_berakhir', isset($rekrutmen) && $rekrutmen->tanggal_berakhir ? $rekrutmen->tanggal_berakhir->format('Y-m-d') : '') }}"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('tanggal_berakhir') border-danger-400 @enderror">
            @error('tanggal_berakhir') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Deskripsi --}}
    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4"
                  placeholder="Deskripsi mengenai rekrutmen ini..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('deskripsi', $rekrutmen->deskripsi ?? '') }}</textarea>
    </div>

    {{-- Persyaratan --}}
    <div>
        <label for="persyaratan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Persyaratan</label>
        <textarea name="persyaratan" id="persyaratan" rows="5"
                  placeholder="Persyaratan pendaftaran (pisahkan per baris)..."
                  class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">{{ old('persyaratan', $rekrutmen->persyaratan ?? '') }}</textarea>
    </div>

    {{-- Poster --}}
    <div>
        <label for="poster" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Poster</label>
        @if(isset($rekrutmen) && $rekrutmen->poster)
            <div class="mt-2 mb-2">
                <img src="{{ Storage::url($rekrutmen->poster) }}" alt="Poster" class="h-32 w-auto rounded-lg border border-gray-200 dark:border-gray-700">
            </div>
        @endif
        <input type="file" name="poster" id="poster" accept="image/jpeg,image/png,image/webp"
               class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-primary-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400 @error('poster') border-danger-400 @enderror">
        <p class="mt-1 text-xs text-gray-400">Format: JPG, PNG, WEBP. Maks 2MB.</p>
        @error('poster') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>

    {{-- Status --}}
    <div>
        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status <span class="text-danger-500">*</span></label>
        <select name="status" id="status"
                class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('status') border-danger-400 @enderror">
            @foreach(\App\Models\Rekrutmen::STATUSES as $val => $label)
                <option value="{{ $val }}" {{ old('status', $rekrutmen->status ?? 'draft') === $val ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
    </div>
</div>
