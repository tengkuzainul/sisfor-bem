<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Keanggotaan;
use App\Models\Kepengurusan;
use App\Models\ProgramKerja;
use App\Models\Rekrutmen;

class HomeController extends Controller
{
    public function index()
    {
        $kepengurusan = Kepengurusan::getActive();

        if (! $kepengurusan) {
            return view('home', [
                'kepengurusan' => null,
                'departemen' => collect(),
                'jabatan' => collect(),
                'bph' => collect(),
                'anggotaByDept' => collect(),
                'totalAnggota' => 0,
                'upcomingProker' => collect(),
                'openRecruitments' => Rekrutmen::open()
                    ->with('kepengurusan')
                    ->withCount('pendaftar')
                    ->orderByDesc('tanggal_mulai')
                    ->get(),
            ]);
        }

        // Get departemen for active kepengurusan
        $departemen = Departemen::where('kepengurusan_id', $kepengurusan->id)
            ->orderBy('nama')
            ->get();

        // Get all jabatan ordered by level
        $jabatan = Jabatan::ordered()->get();

        // Get all active keanggotaan with relations
        $keanggotaan = Keanggotaan::with(['anggota', 'jabatan', 'departemen'])
            ->where('kepengurusan_id', $kepengurusan->id)
            ->where('status', 'aktif')
            ->get();

        // BPH (no departemen assigned) - sorted by jabatan level
        $bph = $keanggotaan->whereNull('departemen_id')
            ->sortBy(fn ($k) => $k->jabatan->level ?? 99);

        // Group anggota by departemen
        $anggotaByDept = $keanggotaan->whereNotNull('departemen_id')
            ->groupBy('departemen_id')
            ->map(fn ($group) => $group->sortBy(fn ($k) => $k->jabatan->level ?? 99));

        $totalAnggota = $keanggotaan->count();

        // Upcoming / recent program kerja for homepage
        $upcomingProker = ProgramKerja::with(['kategori', 'departemen'])
            ->where('kepengurusan_id', $kepengurusan->id)
            ->whereIn('status', ['coming_soon', 'berlangsung'])
            ->orderBy('tanggal_mulai')
            ->limit(6)
            ->get();

        // Open recruitments
        $openRecruitments = Rekrutmen::open()
            ->with('kepengurusan')
            ->withCount('pendaftar')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('home', compact(
            'kepengurusan',
            'departemen',
            'jabatan',
            'bph',
            'anggotaByDept',
            'totalAnggota',
            'upcomingProker',
            'openRecruitments'
        ));
    }

    /**
     * Frontend detail page for a program kerja.
     */
    public function prokerDetail(ProgramKerja $programKerja)
    {
        $programKerja->load(['kategori', 'departemen', 'kepengurusan', 'dokumentasi']);

        return view('proker-detail', compact('programKerja'));
    }
}
