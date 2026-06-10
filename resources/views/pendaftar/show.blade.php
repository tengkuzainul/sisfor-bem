@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('pendaftar.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Pendaftar</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $pendaftar->nama_lengkap }}</span>
@endsection

@section('page-header')
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $pendaftar->nama_lengkap }}</h1>
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-semibold"
                      style="background: {{ $pendaftar->status_color }}15; color: {{ $pendaftar->status_color }}">
                    <span class="h-1.5 w-1.5 rounded-full" style="background: {{ $pendaftar->status_color }}"></span>
                    {{ $pendaftar->status_label }}
                </span>
            </div>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                {{ $pendaftar->kode_pendaftaran }} &bull; {{ $pendaftar->rekrutmen->judul ?? '-' }}
            </p>
        </div>

        {{-- Status Change --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <x-heroicon-o-arrow-path class="h-4 w-4" />
                Ubah Status
            </button>
            <div x-show="open" x-cloak @click.outside="open = false" x-transition
                 class="absolute right-0 top-full z-10 mt-1 w-56 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800">
                @foreach(\App\Models\Pendaftar::STATUSES as $val => $label)
                    @if($val !== $pendaftar->status)
                        <form method="POST" action="{{ route('pendaftar.update-status', $pendaftar) }}">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $val }}">
                            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700">
                                <span class="h-2 w-2 rounded-full" style="background: {{ \App\Models\Pendaftar::STATUSES[$val] ? (new \App\Models\Pendaftar(['status' => $val]))->status_color : '#6b7280' }}"></span>
                                {{ $label }}
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- LEFT: Data Pendaftar --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Data Pribadi --}}
            <div class="glass-card p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Data Pribadi</h3>
                <dl class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @php
                        $fields = [
                            ['Nama Lengkap', $pendaftar->nama_lengkap],
                            ['NIM', $pendaftar->nim],
                            ['Email', $pendaftar->email],
                            ['No. HP', $pendaftar->no_hp],
                            ['Tempat, Tanggal Lahir', $pendaftar->tempat_lahir . ', ' . $pendaftar->tanggal_lahir->format('d M Y')],
                            ['Jenis Kelamin', $pendaftar->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
                            ['Program Studi', $pendaftar->prodi],
                            ['Angkatan', $pendaftar->angkatan],
                        ];
                    @endphp
                    @foreach($fields as $f)
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">{{ $f[0] }}</dt>
                            <dd class="mt-0.5 text-sm font-medium text-gray-900 dark:text-white">{{ $f[1] }}</dd>
                        </div>
                    @endforeach
                    <div class="sm:col-span-2">
                        <dt class="text-xs text-gray-500 dark:text-gray-400">Alamat</dt>
                        <dd class="mt-0.5 text-sm text-gray-900 dark:text-white">{{ $pendaftar->alamat }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Pilihan Departemen & Motivasi --}}
            <div class="glass-card p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Pilihan & Motivasi</h3>
                <dl class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Departemen Pilihan 1</dt>
                            <dd class="mt-0.5">
                                <span class="inline-flex items-center rounded-lg bg-primary-50 px-2.5 py-1 text-sm font-medium text-primary-700 dark:bg-primary-900/30 dark:text-primary-400">
                                    {{ $pendaftar->departemenPilihan1->nama ?? '-' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Departemen Pilihan 2</dt>
                            <dd class="mt-0.5">
                                @if($pendaftar->departemenPilihan2)
                                    <span class="inline-flex items-center rounded-lg bg-gray-100 px-2.5 py-1 text-sm font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        {{ $pendaftar->departemenPilihan2->nama }}
                                    </span>
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            </dd>
                        </div>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-500 dark:text-gray-400">Motivasi</dt>
                        <dd class="mt-1 text-sm leading-relaxed text-gray-700 whitespace-pre-line dark:text-gray-300">{{ $pendaftar->motivasi }}</dd>
                    </div>
                    @if($pendaftar->pengalaman_organisasi)
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Pengalaman Organisasi</dt>
                            <dd class="mt-1 text-sm leading-relaxed text-gray-700 whitespace-pre-line dark:text-gray-300">{{ $pendaftar->pengalaman_organisasi }}</dd>
                        </div>
                    @endif
                    @if($pendaftar->keahlian)
                        <div>
                            <dt class="text-xs text-gray-500 dark:text-gray-400">Keahlian / Skill</dt>
                            <dd class="mt-1 text-sm text-gray-700 dark:text-gray-300">{{ $pendaftar->keahlian }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Dokumen --}}
            <div class="glass-card p-6">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Dokumen</h3>
                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    {{-- Foto --}}
                    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Foto</p>
                        @if($pendaftar->foto)
                            <img src="{{ Storage::url($pendaftar->foto) }}" alt="Foto" class="mt-2 h-24 w-auto rounded-lg">
                        @else
                            <p class="mt-2 text-sm text-gray-400">Tidak ada</p>
                        @endif
                    </div>
                    {{-- CV --}}
                    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">CV / Resume</p>
                        @if($pendaftar->cv_file)
                            <a href="{{ Storage::url($pendaftar->cv_file) }}" target="_blank"
                               class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">
                                <x-heroicon-o-document-arrow-down class="h-4 w-4" /> Download
                            </a>
                        @else
                            <p class="mt-2 text-sm text-gray-400">Tidak ada</p>
                        @endif
                    </div>
                    {{-- Sertifikat --}}
                    <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Sertifikat</p>
                        @if($pendaftar->sertifikat_file)
                            <a href="{{ Storage::url($pendaftar->sertifikat_file) }}" target="_blank"
                               class="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">
                                <x-heroicon-o-document-arrow-down class="h-4 w-4" /> Download
                            </a>
                        @else
                            <p class="mt-2 text-sm text-gray-400">Tidak ada</p>
                        @endif
                    </div>
                </div>
                @if($pendaftar->link_portfolio)
                    <div class="mt-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Portfolio / Social Media</p>
                        <a href="{{ $pendaftar->link_portfolio }}" target="_blank"
                           class="mt-0.5 text-sm font-medium text-primary-600 hover:underline dark:text-primary-400">
                            {{ $pendaftar->link_portfolio }}
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Review Panel --}}
        <div class="space-y-6">

            {{-- Rekomendasi Summary --}}
            @php
                $rekom = $pendaftar->getRekomendasi();
            @endphp
            <div class="glass-card p-5">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Ringkasan Rekomendasi</h3>
                <div class="mt-3 grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-lg bg-success-50 p-3 dark:bg-success-900/20">
                        <p class="text-xl font-bold text-success-700 dark:text-success-400">{{ $rekom['direkomendasikan'] }}</p>
                        <p class="text-[10px] text-success-600 dark:text-success-500">Direkomen</p>
                    </div>
                    <div class="rounded-lg bg-danger-50 p-3 dark:bg-danger-900/20">
                        <p class="text-xl font-bold text-danger-700 dark:text-danger-400">{{ $rekom['tidak_direkomendasikan'] }}</p>
                        <p class="text-[10px] text-danger-600 dark:text-danger-500">Tidak</p>
                    </div>
                    <div class="rounded-lg bg-warning-50 p-3 dark:bg-warning-900/20">
                        <p class="text-xl font-bold text-warning-700 dark:text-warning-400">{{ $rekom['netral'] }}</p>
                        <p class="text-[10px] text-warning-600 dark:text-warning-500">Netral</p>
                    </div>
                </div>
            </div>

            {{-- Review Form --}}
            <div class="glass-card p-5" x-data="{ tipe: 'saran' }">
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Tambah Review</h3>
                <form method="POST" action="{{ route('pendaftar.review', $pendaftar) }}" class="mt-4 space-y-4">
                    @csrf

                    {{-- Tipe --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Tipe Review</label>
                        <select name="tipe" x-model="tipe"
                                class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            @foreach(\App\Models\PendaftarReview::TIPE_LABELS as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Departemen (optional) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Dari Departemen <span class="text-gray-400">(opsional)</span></label>
                        <select name="departemen_id"
                                class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">— Tidak Ada —</option>
                            @foreach($departemenList as $d)
                                <option value="{{ $d->id }}">{{ $d->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Komentar --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Komentar</label>
                        <textarea name="komentar" rows="3" required minlength="10" placeholder="Tuliskan saran, kritik, atau rekomendasi..."
                                  class="mt-1 w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"></textarea>
                        @error('komentar') <p class="mt-1 text-xs text-danger-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Rekomendasi Status (only for tipe=rekomendasi) --}}
                    <div x-show="tipe === 'rekomendasi'" x-transition>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300">Status Rekomendasi</label>
                        <div class="mt-2 space-y-2">
                            @foreach(\App\Models\PendaftarReview::REKOMENDASI_LABELS as $val => $label)
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-gray-200 px-3 py-2 transition hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="radio" name="rekomendasi_status" value="{{ $val }}" class="text-primary-600 focus:ring-primary-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">
                        Kirim Review
                    </button>
                </form>
            </div>

            {{-- Catatan Admin --}}
            @if($pendaftar->catatan_admin)
                <div class="glass-card p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Catatan Admin</h3>
                    <p class="mt-2 text-sm text-gray-600 whitespace-pre-line dark:text-gray-400">{{ $pendaftar->catatan_admin }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Review Timeline --}}
    <div class="mt-6 glass-card">
        <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white">Riwayat Review</h3>
            <span class="rounded-lg bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                {{ $pendaftar->reviews->count() }} review
            </span>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-800">
            @forelse($pendaftar->reviews as $review)
                <div class="px-6 py-4">
                    <div class="flex items-start gap-3">
                        {{-- Avatar --}}
                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold text-white"
                             style="background: {{ $review->tipe_color }}">
                            {{ strtoupper(substr($review->reviewer->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->reviewer->name ?? 'User' }}</span>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                      style="background: {{ $review->tipe_color }}15; color: {{ $review->tipe_color }}">
                                    {{ $review->tipe_label }}
                                </span>
                                @if($review->departemen)
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500">— {{ $review->departemen->nama }}</span>
                                @endif
                            </div>
                            <p class="mt-1 text-sm text-gray-600 whitespace-pre-line dark:text-gray-400">{{ $review->komentar }}</p>
                            @if($review->rekomendasi_status)
                                <div class="mt-2">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-semibold"
                                          style="background: {{ $review->rekomendasi_color }}15; color: {{ $review->rekomendasi_color }}">
                                        <span class="h-1.5 w-1.5 rounded-full" style="background: {{ $review->rekomendasi_color }}"></span>
                                        {{ $review->rekomendasi_label }}
                                    </span>
                                </div>
                            @endif
                            <p class="mt-1 text-[10px] text-gray-400 dark:text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada review untuk pendaftar ini.
                </div>
            @endforelse
        </div>
    </div>
@endsection
