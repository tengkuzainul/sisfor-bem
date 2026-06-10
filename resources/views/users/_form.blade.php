{{-- Form partial for User create/edit --}}
<div class="space-y-5">
    <div class="grid gap-5 sm:grid-cols-2">
        {{-- Nama --}}
        <div class="sm:col-span-2">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama <span class="text-danger-500">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? '') }}"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('name') border-danger-400 @enderror"
                   placeholder="Nama lengkap" required>
            @error('name') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-danger-500">*</span></label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? '') }}"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('email') border-danger-400 @enderror"
                   placeholder="email@example.com" required>
            @error('email') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>

        {{-- Role --}}
        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role <span class="text-danger-500">*</span></label>
            <select name="role" id="role"
                    class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('role') border-danger-400 @enderror" required>
                @foreach($roles as $value => $label)
                    <option value="{{ $value }}" {{ old('role', $user->role ?? 'pengurus') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('role') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}
                @if(!isset($user)) <span class="text-danger-500">*</span> @endif
            </label>
            <input type="password" name="password" id="password"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30 @error('password') border-danger-400 @enderror"
                   placeholder="••••••••" {{ isset($user) ? '' : 'required' }}>
            @error('password') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                   placeholder="••••••••" {{ isset($user) ? '' : 'required' }}>
        </div>

        {{-- Anggota Terkait --}}
        <div class="sm:col-span-2">
            <label for="anggota_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hubungkan dengan Anggota</label>
            <select name="anggota_id" id="anggota_id"
                    class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30">
                <option value="">-- Tidak dihubungkan --</option>
                @foreach($anggotaList as $anggota)
                    <option value="{{ $anggota->id }}" {{ old('anggota_id', $user->anggota_id ?? '') == $anggota->id ? 'selected' : '' }}>
                        {{ $anggota->nama }} ({{ $anggota->nim }})
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Opsional: hubungkan pengguna ini dengan data anggota.</p>
        </div>
    </div>
</div>
