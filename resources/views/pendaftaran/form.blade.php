@extends('layouts.pendaftaran')

@section('content')
    <div class="mx-auto max-w-3xl" x-data="wizardForm()" x-cloak>

        {{-- Rekrutmen Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $rekrutmen->judul }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Pendaftaran ditutup {{ $rekrutmen->tanggal_berakhir->format('d F Y') }}
            </p>
        </div>

        {{-- Step Indicator --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <template x-for="(s, i) in steps" :key="i">
                    <div class="flex items-center" :class="i < steps.length - 1 ? 'flex-1' : ''">
                        <div class="flex flex-col items-center">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full text-sm font-bold transition-all duration-300"
                                 :class="step > i + 1 ? 'bg-success-500 text-white' : (step === i + 1 ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400')">
                                <template x-if="step > i + 1">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                </template>
                                <template x-if="step <= i + 1">
                                    <span x-text="i + 1"></span>
                                </template>
                            </div>
                            <span class="mt-2 hidden text-[10px] font-medium sm:block"
                                  :class="step >= i + 1 ? 'text-primary-600 dark:text-primary-400' : 'text-gray-400 dark:text-gray-500'"
                                  x-text="s"></span>
                        </div>
                        <template x-if="i < steps.length - 1">
                            <div class="mx-2 h-0.5 flex-1 rounded transition-all duration-300"
                                 :class="step > i + 1 ? 'bg-success-500' : 'bg-gray-200 dark:bg-gray-700'"></div>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('pendaftaran.store', $rekrutmen->slug) }}" method="POST" enctype="multipart/form-data"
              @submit="handleSubmit($event)">
            @csrf

            {{-- ========================================
                 STEP 1: Data Pribadi
                 ======================================== --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Data Pribadi</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Lengkapi data diri anda dengan benar.</p>

                    <div class="mt-6 space-y-5">
                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap <span class="text-danger-500">*</span></label>
                            <input type="text" name="nama_lengkap" x-model="form.nama_lengkap" placeholder="Nama lengkap sesuai KTP"
                                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                   :class="errors.nama_lengkap ? 'border-danger-400' : ''">
                            <p x-show="errors.nama_lengkap" x-text="errors.nama_lengkap" class="mt-1 text-xs text-danger-600"></p>
                            @error('nama_lengkap') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- NIM & Email --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIM <span class="text-danger-500">*</span></label>
                                <input type="text" name="nim" x-model="form.nim" placeholder="e.g. 2024123456"
                                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                       :class="errors.nim ? 'border-danger-400' : ''">
                                <p x-show="errors.nim" x-text="errors.nim" class="mt-1 text-xs text-danger-600"></p>
                                @error('nim') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email <span class="text-danger-500">*</span></label>
                                <input type="email" name="email" x-model="form.email" placeholder="email@student.ac.id"
                                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                       :class="errors.email ? 'border-danger-400' : ''">
                                <p x-show="errors.email" x-text="errors.email" class="mt-1 text-xs text-danger-600"></p>
                                @error('email') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- No HP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No. HP / WhatsApp <span class="text-danger-500">*</span></label>
                            <input type="text" name="no_hp" x-model="form.no_hp" placeholder="08xxxxxxxxxx"
                                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                   :class="errors.no_hp ? 'border-danger-400' : ''">
                            <p x-show="errors.no_hp" x-text="errors.no_hp" class="mt-1 text-xs text-danger-600"></p>
                            @error('no_hp') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tempat & Tanggal Lahir --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tempat Lahir <span class="text-danger-500">*</span></label>
                                <input type="text" name="tempat_lahir" x-model="form.tempat_lahir" placeholder="Kota tempat lahir"
                                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                       :class="errors.tempat_lahir ? 'border-danger-400' : ''">
                                <p x-show="errors.tempat_lahir" x-text="errors.tempat_lahir" class="mt-1 text-xs text-danger-600"></p>
                                @error('tempat_lahir') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Lahir <span class="text-danger-500">*</span></label>
                                <input type="date" name="tanggal_lahir" x-model="form.tanggal_lahir"
                                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                       :class="errors.tanggal_lahir ? 'border-danger-400' : ''">
                                <p x-show="errors.tanggal_lahir" x-text="errors.tanggal_lahir" class="mt-1 text-xs text-danger-600"></p>
                                @error('tanggal_lahir') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Jenis Kelamin --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jenis Kelamin <span class="text-danger-500">*</span></label>
                            <div class="mt-2 flex gap-4">
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition"
                                       :class="form.jenis_kelamin === 'L' ? 'border-primary-500 bg-primary-50 dark:bg-primary-950/30 dark:border-primary-600' : 'border-gray-200 dark:border-gray-700'">
                                    <input type="radio" name="jenis_kelamin" value="L" x-model="form.jenis_kelamin" class="text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Laki-laki</span>
                                </label>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border px-4 py-2.5 transition"
                                       :class="form.jenis_kelamin === 'P' ? 'border-primary-500 bg-primary-50 dark:bg-primary-950/30 dark:border-primary-600' : 'border-gray-200 dark:border-gray-700'">
                                    <input type="radio" name="jenis_kelamin" value="P" x-model="form.jenis_kelamin" class="text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Perempuan</span>
                                </label>
                            </div>
                            <p x-show="errors.jenis_kelamin" x-text="errors.jenis_kelamin" class="mt-1 text-xs text-danger-600"></p>
                            @error('jenis_kelamin') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Prodi & Angkatan --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Program Studi <span class="text-danger-500">*</span></label>
                                <input type="text" name="prodi" x-model="form.prodi" placeholder="e.g. Sistem Informasi"
                                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                       :class="errors.prodi ? 'border-danger-400' : ''">
                                <p x-show="errors.prodi" x-text="errors.prodi" class="mt-1 text-xs text-danger-600"></p>
                                @error('prodi') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Angkatan <span class="text-danger-500">*</span></label>
                                <input type="text" name="angkatan" x-model="form.angkatan" placeholder="e.g. 2024" maxlength="4"
                                       class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                       :class="errors.angkatan ? 'border-danger-400' : ''">
                                <p x-show="errors.angkatan" x-text="errors.angkatan" class="mt-1 text-xs text-danger-600"></p>
                                @error('angkatan') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Alamat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat <span class="text-danger-500">*</span></label>
                            <textarea name="alamat" x-model="form.alamat" rows="2" placeholder="Alamat lengkap saat ini"
                                      class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                      :class="errors.alamat ? 'border-danger-400' : ''"></textarea>
                            <p x-show="errors.alamat" x-text="errors.alamat" class="mt-1 text-xs text-danger-600"></p>
                            @error('alamat') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Foto --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Diri</label>
                            <input type="file" name="foto" accept="image/jpeg,image/png" @change="form.foto = $event.target.files[0]?.name || ''"
                                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-primary-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
                            <p class="mt-1 text-xs text-gray-400">JPG/PNG, maks 2MB.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================
                 STEP 2: Pilihan Departemen & Motivasi
                 ======================================== --}}
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Pilihan & Motivasi</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih departemen yang anda minati dan ceritakan motivasi anda.</p>

                    <div class="mt-6 space-y-5">
                        {{-- Departemen Pilihan 1 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departemen Pilihan 1 <span class="text-danger-500">*</span></label>
                            <select name="departemen_pilihan_1" x-model="form.departemen_pilihan_1"
                                    class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                    :class="errors.departemen_pilihan_1 ? 'border-danger-400' : ''">
                                <option value="">— Pilih Departemen —</option>
                                @foreach($departemen as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                            <p x-show="errors.departemen_pilihan_1" x-text="errors.departemen_pilihan_1" class="mt-1 text-xs text-danger-600"></p>
                            @error('departemen_pilihan_1') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Departemen Pilihan 2 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Departemen Pilihan 2 <span class="text-gray-400">(opsional)</span></label>
                            <select name="departemen_pilihan_2" x-model="form.departemen_pilihan_2"
                                    class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                <option value="">— Pilih Departemen —</option>
                                @foreach($departemen as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->nama }}</option>
                                @endforeach
                            </select>
                            @error('departemen_pilihan_2') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Motivasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivasi Bergabung <span class="text-danger-500">*</span></label>
                            <textarea name="motivasi" x-model="form.motivasi" rows="4" placeholder="Ceritakan mengapa anda tertarik bergabung dengan BEM (minimal 50 karakter)..."
                                      class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                      :class="errors.motivasi ? 'border-danger-400' : ''"></textarea>
                            <div class="mt-1 flex items-center justify-between">
                                <p x-show="errors.motivasi" x-text="errors.motivasi" class="text-xs text-danger-600"></p>
                                <span class="text-xs" :class="form.motivasi.length >= 50 ? 'text-success-600' : 'text-gray-400'" x-text="form.motivasi.length + '/50 karakter'"></span>
                            </div>
                            @error('motivasi') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Pengalaman Organisasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pengalaman Organisasi</label>
                            <textarea name="pengalaman_organisasi" x-model="form.pengalaman_organisasi" rows="3" placeholder="Pengalaman organisasi sebelumnya (jika ada)..."
                                      class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"></textarea>
                        </div>

                        {{-- Keahlian --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Keahlian / Skill</label>
                            <textarea name="keahlian" x-model="form.keahlian" rows="2" placeholder="e.g. Desain grafis, Public speaking, Programming, dll."
                                      class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================
                 STEP 3: Dokumen Pendukung
                 ======================================== --}}
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Dokumen Pendukung</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Upload dokumen pendukung untuk melengkapi pendaftaran.</p>

                    <div class="mt-6 space-y-5">
                        {{-- CV --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CV / Resume</label>
                            <input type="file" name="cv_file" accept=".pdf"
                                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-primary-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
                            <p class="mt-1 text-xs text-gray-400">Format PDF, maks 5MB.</p>
                            @error('cv_file') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Sertifikat --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sertifikat / Piagam</label>
                            <input type="file" name="sertifikat_file" accept=".pdf,.jpg,.jpeg,.png"
                                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-1.5 file:text-sm file:font-medium file:text-primary-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-primary-900/30 dark:file:text-primary-400">
                            <p class="mt-1 text-xs text-gray-400">Format PDF/JPG/PNG, maks 5MB.</p>
                            @error('sertifikat_file') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Link Portfolio --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Link Portfolio / Social Media</label>
                            <input type="url" name="link_portfolio" x-model="form.link_portfolio" placeholder="https://..."
                                   class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            @error('link_portfolio') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================
                 STEP 4: Review & Submit
                 ======================================== --}}
            <div x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 sm:p-8">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Review Data Anda</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Periksa kembali data sebelum mengirim.</p>

                    <div class="mt-6 space-y-6">
                        {{-- Data Pribadi Review --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Data Pribadi</h3>
                                <button type="button" @click="step = 1" class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">Ubah</button>
                            </div>
                            <dl class="mt-3 divide-y divide-gray-100 dark:divide-gray-800">
                                <template x-for="field in [
                                    {l: 'Nama Lengkap', v: form.nama_lengkap},
                                    {l: 'NIM', v: form.nim},
                                    {l: 'Email', v: form.email},
                                    {l: 'No. HP', v: form.no_hp},
                                    {l: 'TTL', v: form.tempat_lahir + ', ' + form.tanggal_lahir},
                                    {l: 'Jenis Kelamin', v: form.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'},
                                    {l: 'Program Studi', v: form.prodi},
                                    {l: 'Angkatan', v: form.angkatan},
                                    {l: 'Alamat', v: form.alamat}
                                ]" :key="field.l">
                                    <div class="flex justify-between py-2 text-sm">
                                        <dt class="text-gray-500 dark:text-gray-400" x-text="field.l"></dt>
                                        <dd class="font-medium text-gray-900 dark:text-white text-right max-w-[60%]" x-text="field.v || '-'"></dd>
                                    </div>
                                </template>
                            </dl>
                        </div>

                        {{-- Pilihan & Motivasi Review --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Pilihan & Motivasi</h3>
                                <button type="button" @click="step = 2" class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">Ubah</button>
                            </div>
                            <dl class="mt-3 divide-y divide-gray-100 dark:divide-gray-800">
                                <div class="flex justify-between py-2 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Dept. Pilihan 1</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white" x-text="getDeptName(form.departemen_pilihan_1) || '-'"></dd>
                                </div>
                                <div class="flex justify-between py-2 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Dept. Pilihan 2</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white" x-text="getDeptName(form.departemen_pilihan_2) || '-'"></dd>
                                </div>
                                <div class="py-2 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Motivasi</dt>
                                    <dd class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-line" x-text="form.motivasi || '-'"></dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Dokumen Review --}}
                        <div>
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Dokumen</h3>
                                <button type="button" @click="step = 3" class="text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">Ubah</button>
                            </div>
                            <dl class="mt-3 divide-y divide-gray-100 dark:divide-gray-800">
                                <div class="flex justify-between py-2 text-sm">
                                    <dt class="text-gray-500 dark:text-gray-400">Link Portfolio</dt>
                                    <dd class="font-medium text-gray-900 dark:text-white" x-text="form.link_portfolio || '-'"></dd>
                                </div>
                            </dl>
                        </div>

                        {{-- Agreement --}}
                        <div class="rounded-lg border border-primary-200 bg-primary-50 p-4 dark:border-primary-800 dark:bg-primary-950/30">
                            <label class="flex cursor-pointer items-start gap-3">
                                <input type="checkbox" x-model="agreed" class="mt-0.5 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">
                                    Saya menyatakan bahwa data yang saya isi adalah benar dan saya bersedia mengikuti seluruh rangkaian proses rekrutmen BEM Sistem Informasi.
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navigation Buttons --}}
            <div class="mt-6 flex items-center justify-between">
                <button type="button" @click="prevStep()" x-show="step > 1"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" /></svg>
                    Kembali
                </button>
                <div x-show="step === 1"></div>

                <button type="button" @click="nextStep()" x-show="step < 4"
                        class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-primary-700">
                    Selanjutnya
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" /></svg>
                </button>

                <button type="submit" x-show="step === 4" :disabled="!agreed || submitting"
                        class="inline-flex items-center gap-2 rounded-lg bg-success-600 px-6 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-success-700 disabled:cursor-not-allowed disabled:opacity-50">
                    <svg x-show="!submitting" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" /></svg>
                    <svg x-show="submitting" class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                    <span x-text="submitting ? 'Mengirim...' : 'Kirim Pendaftaran'"></span>
                </button>
            </div>
        </form>
    </div>

    <script>
        function wizardForm() {
            const departemen = @json($departemen);
            return {
                step: 1,
                steps: ['Data Pribadi', 'Pilihan & Motivasi', 'Dokumen', 'Review'],
                agreed: false,
                submitting: false,
                errors: {},
                form: {
                    nama_lengkap: '{{ old('nama_lengkap', '') }}',
                    nim: '{{ old('nim', '') }}',
                    email: '{{ old('email', '') }}',
                    no_hp: '{{ old('no_hp', '') }}',
                    tempat_lahir: '{{ old('tempat_lahir', '') }}',
                    tanggal_lahir: '{{ old('tanggal_lahir', '') }}',
                    jenis_kelamin: '{{ old('jenis_kelamin', '') }}',
                    prodi: '{{ old('prodi', '') }}',
                    angkatan: '{{ old('angkatan', '') }}',
                    alamat: '{{ old('alamat', '') }}',
                    foto: '',
                    departemen_pilihan_1: '{{ old('departemen_pilihan_1', '') }}',
                    departemen_pilihan_2: '{{ old('departemen_pilihan_2', '') }}',
                    motivasi: '{{ old('motivasi', '') }}',
                    pengalaman_organisasi: '{{ old('pengalaman_organisasi', '') }}',
                    keahlian: '{{ old('keahlian', '') }}',
                    link_portfolio: '{{ old('link_portfolio', '') }}',
                },

                getDeptName(id) {
                    if (!id) return null;
                    const dept = departemen.find(d => d.id == id);
                    return dept ? dept.nama : '-';
                },

                validateStep(s) {
                    this.errors = {};
                    let valid = true;

                    if (s === 1) {
                        if (!this.form.nama_lengkap.trim()) { this.errors.nama_lengkap = 'Nama lengkap wajib diisi'; valid = false; }
                        if (!this.form.nim.trim()) { this.errors.nim = 'NIM wajib diisi'; valid = false; }
                        if (!this.form.email.trim()) { this.errors.email = 'Email wajib diisi'; valid = false; }
                        if (!this.form.no_hp.trim()) { this.errors.no_hp = 'No. HP wajib diisi'; valid = false; }
                        if (!this.form.tempat_lahir.trim()) { this.errors.tempat_lahir = 'Tempat lahir wajib diisi'; valid = false; }
                        if (!this.form.tanggal_lahir) { this.errors.tanggal_lahir = 'Tanggal lahir wajib diisi'; valid = false; }
                        if (!this.form.jenis_kelamin) { this.errors.jenis_kelamin = 'Jenis kelamin wajib dipilih'; valid = false; }
                        if (!this.form.prodi.trim()) { this.errors.prodi = 'Program studi wajib diisi'; valid = false; }
                        if (!this.form.angkatan.trim()) { this.errors.angkatan = 'Angkatan wajib diisi'; valid = false; }
                        if (!this.form.alamat.trim()) { this.errors.alamat = 'Alamat wajib diisi'; valid = false; }
                    }

                    if (s === 2) {
                        if (!this.form.departemen_pilihan_1) { this.errors.departemen_pilihan_1 = 'Departemen pilihan 1 wajib dipilih'; valid = false; }
                        if (!this.form.motivasi.trim()) { this.errors.motivasi = 'Motivasi wajib diisi'; valid = false; }
                        else if (this.form.motivasi.trim().length < 50) { this.errors.motivasi = 'Motivasi minimal 50 karakter'; valid = false; }
                    }

                    return valid;
                },

                nextStep() {
                    if (this.validateStep(this.step)) {
                        this.step++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    this.step--;
                    this.errors = {};
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                handleSubmit(e) {
                    if (!this.agreed) {
                        e.preventDefault();
                        return;
                    }
                    this.submitting = true;
                }
            };
        }
    </script>
@endsection
