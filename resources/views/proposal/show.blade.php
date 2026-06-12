@extends('layouts.app')

@section('breadcrumb')
    <a href="{{ route('proposal.index') }}"
        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">Proposal</a>
    <x-heroicon-o-chevron-right class="h-3.5 w-3.5 text-gray-300 dark:text-gray-600" />
    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Detail</span>
@endsection

@section('page-header')
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $proposal->judul }}</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Detail dan riwayat review proposal.</p>
    </div>
@endsection

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">

        {{-- ====== Progress Steps ====== --}}
        <div class="glass-card p-6">
            <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Alur Persetujuan
            </h3>
            @php
                $steps = [
                    ['label' => 'Diajukan', 'done' => true],
                    [
                        'label' => 'Review Pembina',
                        'done' =>
                            in_array($proposal->status, ['review_pembina', 'revisi_pembina', 'disetujui', 'ditolak']) ||
                            $proposal->status === 'review_pembina',
                    ],
                    ['label' => 'Disetujui', 'done' => $proposal->status === 'disetujui'],
                ];
                $isRejected = $proposal->status === 'ditolak';
                $isRevision = in_array($proposal->status, ['revisi_pembina']);
            @endphp
            <div class="flex items-center justify-between">
                @foreach ($steps as $i => $step)
                    <div class="flex flex-col items-center text-center" style="flex: 1">
                        <div
                            class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold transition
                            {{ $step['done'] ? 'bg-success-500 text-white' : ($isRejected && $i >= 1 ? 'bg-danger-100 text-danger-600 dark:bg-danger-900/30 dark:text-danger-400' : 'bg-gray-100 text-gray-400 dark:bg-gray-800') }}">
                            @if ($step['done'])
                                <x-heroicon-s-check class="h-4 w-4" />
                            @elseif($isRejected)
                                <x-heroicon-s-x-mark class="h-4 w-4" />
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span
                            class="mt-1.5 text-[10px] font-medium {{ $step['done'] ? 'text-success-600 dark:text-success-400' : 'text-gray-400 dark:text-gray-500' }}">{{ $step['label'] }}</span>
                    </div>
                    @if ($i < count($steps) - 1)
                        <div
                            class="h-0.5 flex-1 {{ $steps[$i + 1]['done'] ? 'bg-success-400' : 'bg-gray-200 dark:bg-gray-700' }}">
                        </div>
                    @endif
                @endforeach
            </div>

            @if ($isRevision)
                <div
                    class="mt-4 rounded-lg bg-warning-50 p-3 text-sm text-warning-700 dark:bg-warning-950/20 dark:text-warning-400">
                    <x-heroicon-o-exclamation-triangle class="mb-0.5 inline h-4 w-4" />
                    Proposal perlu direvisi. Silakan upload ulang proposal yang telah diperbaiki.
                </div>
            @endif

            @if ($isRejected)
                <div
                    class="mt-4 rounded-lg bg-danger-50 p-3 text-sm text-danger-700 dark:bg-danger-950/20 dark:text-danger-400">
                    <x-heroicon-o-x-circle class="mb-0.5 inline h-4 w-4" />
                    Proposal ini telah ditolak.
                </div>
            @endif

            @if ($proposal->status === 'disetujui')
                <div
                    class="mt-4 rounded-lg bg-success-50 p-3 text-sm text-success-700 dark:bg-success-950/20 dark:text-success-400">
                    <x-heroicon-o-check-circle class="mb-0.5 inline h-4 w-4" />
                    Proposal telah disetujui. Kegiatan dapat dilaksanakan.
                </div>
            @endif
        </div>

        {{-- ====== Proposal Info ====== --}}
        <div class="glass-card p-6">
            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold"
                            style="background-color: {{ $proposal->status_color }}22; color: {{ $proposal->status_color }}">
                            <span class="h-1.5 w-1.5 rounded-full"
                                style="background-color: {{ $proposal->status_color }}"></span>
                            {{ $proposal->status_label }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Program Kerja
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $proposal->programKerja->nama ?? '-' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Pengaju</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $proposal->pengaju->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Tanggal
                        Pengajuan</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $proposal->created_at->translatedFormat('d F Y, H:i') }}</dd>
                </div>

                @if ($proposal->catatan_pengaju)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">Catatan
                            Pengaju</dt>
                        <dd class="mt-1 text-sm leading-relaxed text-gray-700 dark:text-gray-300">{!! nl2br(e($proposal->catatan_pengaju)) !!}
                        </dd>
                    </div>
                @endif

                <div class="sm:col-span-2">
                    <dt class="text-xs font-medium uppercase tracking-wider text-gray-400 dark:text-gray-500">File Proposal
                    </dt>
                    <dd class="mt-2">
                        <a href="{{ asset('storage/' . $proposal->file_proposal) }}" target="_blank"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            <x-heroicon-o-document-arrow-down class="h-5 w-5 text-danger-500" />
                            Lihat / Download PDF
                        </a>
                    </dd>
                </div>
            </div>
        </div>

        {{-- ====== Review Timeline ====== --}}
        <div class="glass-card p-6">
            <h3 class="mb-6 text-sm font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Riwayat Review
            </h3>

            @if ($proposal->reviews->count() > 0)
                <div class="relative border-l-2 border-gray-200 pl-6 dark:border-gray-700">
                    @foreach ($proposal->reviews->reverse() as $review)
                        <div class="relative mb-6 last:mb-0">
                            {{-- Dot --}}
                            <div class="absolute -left-[31px] flex h-4 w-4 items-center justify-center rounded-full border-2 border-white dark:border-gray-900"
                                style="background-color: {{ $review->aksi_color }}">
                            </div>

                            <div
                                class="rounded-lg border border-gray-100 bg-gray-50/50 p-4 dark:border-gray-800 dark:bg-gray-800/30">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-sm font-semibold text-gray-900 dark:text-white">{{ $review->reviewer->name ?? '-' }}</span>
                                        <span
                                            class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-semibold"
                                            style="background-color: {{ $review->aksi_color }}22; color: {{ $review->aksi_color }}">
                                            {{ $review->aksi_label }}
                                        </span>
                                        @if ($review->reviewer)
                                            <span
                                                class="text-[10px] text-gray-400">({{ $review->reviewer->role_label }})</span>
                                        @endif
                                    </div>
                                    <span
                                        class="text-xs text-gray-400">{{ $review->created_at->translatedFormat('d M Y, H:i') }}</span>
                                </div>

                                @if ($review->komentar)
                                    <p class="mt-2 text-sm leading-relaxed text-gray-600 dark:text-gray-400">
                                        {!! nl2br(e($review->komentar)) !!}</p>
                                @endif

                                @if ($review->file_lampiran)
                                    <a href="{{ asset('storage/' . $review->file_lampiran) }}" target="_blank"
                                        class="mt-2 inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:underline dark:text-primary-400">
                                        <x-heroicon-o-paper-clip class="h-3.5 w-3.5" /> Lampiran
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-sm text-gray-400 dark:text-gray-500">Belum ada review.</p>
            @endif
        </div>

        {{-- ====== Action Forms ====== --}}
        @php $user = auth()->user(); @endphp

        {{-- Pengurus: Re-upload after revision --}}
        @if ($user->id === $proposal->user_id && $proposal->canRevise())
            <div class="glass-card p-6">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-warning-600 dark:text-warning-400">
                    Upload Revisi</h3>
                <form action="{{ route('proposal.revise', $proposal) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4">
                    @csrf
                    <div>
                        <label for="file_proposal_revise"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">File Proposal Revisi (PDF)
                            <span class="text-danger-500">*</span></label>
                        <input type="file" name="file_proposal" id="file_proposal_revise" accept=".pdf" required
                            class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-900 file:mr-4 file:rounded-lg file:border-0 file:bg-warning-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-warning-700 hover:file:bg-warning-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:file:bg-warning-900/30 dark:file:text-warning-400">
                    </div>
                    <div>
                        <label for="catatan_revise"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Revisi</label>
                        <textarea name="catatan" id="catatan_revise" rows="2"
                            class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                            placeholder="Jelaskan perubahan yang dilakukan..."></textarea>
                    </div>
                    <button type="submit"
                        class="rounded-lg bg-warning-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-warning-700">Upload
                        Revisi</button>
                </form>
            </div>
        @endif

        {{-- Pembina: Review actions --}}
        @if (($user->isPembina() && $proposal->canReviewByPembina()) || ($user->isAdmin() && $proposal->canReviewByPembina()))
            <div class="glass-card p-6">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-wider text-primary-600 dark:text-primary-400">
                    Review — Pembina</h3>
                <form action="{{ route('proposal.review', $proposal) }}" method="POST" x-data="{ aksi: '' }"
                    class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aksi <span
                                class="text-danger-500">*</span></label>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="aksi" value="approve" x-model="aksi"
                                    class="peer hidden">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-lg border-2 border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition peer-checked:border-success-500 peer-checked:bg-success-50 peer-checked:text-success-700 dark:border-gray-700 dark:text-gray-400 dark:peer-checked:border-success-500 dark:peer-checked:bg-success-950/30 dark:peer-checked:text-success-400">
                                    <x-heroicon-o-check-circle class="h-4 w-4" /> Approve
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="aksi" value="revisi" x-model="aksi" class="peer hidden">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-lg border-2 border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition peer-checked:border-warning-500 peer-checked:bg-warning-50 peer-checked:text-warning-700 dark:border-gray-700 dark:text-gray-400 dark:peer-checked:border-warning-500 dark:peer-checked:bg-warning-950/30 dark:peer-checked:text-warning-400">
                                    <x-heroicon-o-arrow-path class="h-4 w-4" /> Minta Revisi
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="aksi" value="tolak" x-model="aksi" class="peer hidden">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-lg border-2 border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition peer-checked:border-danger-500 peer-checked:bg-danger-50 peer-checked:text-danger-700 dark:border-gray-700 dark:text-gray-400 dark:peer-checked:border-danger-500 dark:peer-checked:bg-danger-950/30 dark:peer-checked:text-danger-400">
                                    <x-heroicon-o-x-circle class="h-4 w-4" /> Tolak
                                </span>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="aksi" value="komentar" x-model="aksi"
                                    class="peer hidden">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-lg border-2 border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition peer-checked:border-info-500 peer-checked:bg-info-50 peer-checked:text-info-700 dark:border-gray-700 dark:text-gray-400 dark:peer-checked:border-info-500 dark:peer-checked:bg-info-950/30 dark:peer-checked:text-info-400">
                                    <x-heroicon-o-chat-bubble-left class="h-4 w-4" /> Komentar
                                </span>
                            </label>
                        </div>
                    </div>

                    <div x-show="aksi" x-transition>
                        <label for="komentar_pembina"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Komentar / Alasan</label>
                        <textarea name="komentar" id="komentar_pembina" rows="3"
                            class="mt-1.5 w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-900 outline-none transition focus:border-primary-400 focus:ring-2 focus:ring-primary-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-primary-500 dark:focus:ring-primary-900/30"
                            placeholder="Tuliskan komentar atau alasan..."></textarea>
                    </div>

                    <div x-show="aksi" x-transition>
                        <button type="submit"
                            class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-primary-700">Kirim</button>
                    </div>
                </form>
            </div>
        @endif


    </div>
@endsection
