{{-- Member card partial for landing page --}}
<div class="group rounded-xl border border-gray-200/60 bg-white p-4 transition hover:shadow-md hover:-translate-y-0.5 dark:border-gray-800 dark:bg-gray-900">
    <div class="flex items-center gap-3">
        {{-- Foto --}}
        @if($member->anggota->foto)
            <img src="{{ asset('storage/' . $member->anggota->foto) }}"
                 alt="{{ $member->anggota->nama }}"
                 class="h-12 w-12 shrink-0 rounded-full object-cover border-2 border-gray-100 dark:border-gray-800">
        @else
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-primary-400 to-primary-600 text-sm font-bold text-white">
                {{ strtoupper(substr($member->anggota->nama, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->anggota->nama)[1] ?? '', 0, 1)) }}
            </div>
        @endif

        <div class="min-w-0 flex-1">
            <h4 class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ $member->anggota->nama }}</h4>
            <p class="truncate text-xs text-primary-600 dark:text-primary-400">{{ $member->jabatan->nama ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-3 flex items-center justify-between">
        <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
            {{ $badge ?? '-' }}
        </span>
        <span class="text-[10px] text-gray-400 dark:text-gray-500">{{ $member->anggota->prodi ?? '' }}</span>
    </div>
</div>
