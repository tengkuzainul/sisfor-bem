<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Pendaftar;
use App\Models\Rekrutmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    /**
     * Public landing — list open recruitments.
     */
    public function index()
    {
        $rekrutmen = Rekrutmen::open()
            ->with('kepengurusan')
            ->withCount('pendaftar')
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('pendaftaran.index', compact('rekrutmen'));
    }

    /**
     * Show the wizard form for a specific recruitment.
     */
    public function form(string $slug)
    {
        $rekrutmen = Rekrutmen::where('slug', $slug)->firstOrFail();

        if (!$rekrutmen->is_open) {
            return redirect()->route('pendaftaran.index')
                ->with('error', 'Pendaftaran untuk rekrutmen ini sudah ditutup.');
        }

        $departemen = Departemen::whereHas('kepengurusan', function ($q) use ($rekrutmen) {
            $q->where('id', $rekrutmen->kepengurusan_id);
        })->orderBy('nama')->get();

        return view('pendaftaran.form', compact('rekrutmen', 'departemen'));
    }

    /**
     * Process the wizard form submission.
     */
    public function store(Request $request, string $slug)
    {
        $rekrutmen = Rekrutmen::where('slug', $slug)->firstOrFail();

        if (!$rekrutmen->is_open) {
            return redirect()->route('pendaftaran.index')
                ->with('error', 'Pendaftaran untuk rekrutmen ini sudah ditutup.');
        }

        $validated = $request->validate([
            // Step 1 — Data Pribadi
            'nama_lengkap'       => 'required|string|max:255',
            'nim'                => 'required|string|max:30',
            'email'              => 'required|email|max:255',
            'no_hp'              => 'required|string|max:20',
            'tempat_lahir'       => 'required|string|max:100',
            'tanggal_lahir'      => 'required|date',
            'jenis_kelamin'      => 'required|in:L,P',
            'prodi'              => 'required|string|max:100',
            'angkatan'           => 'required|string|max:4',
            'alamat'             => 'required|string',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Step 2 — Pilihan & Motivasi
            'departemen_pilihan_1' => 'required|exists:departemen,id',
            'departemen_pilihan_2' => 'nullable|exists:departemen,id|different:departemen_pilihan_1',
            'motivasi'             => 'required|string|min:50',
            'pengalaman_organisasi' => 'nullable|string',
            'keahlian'             => 'nullable|string',

            // Step 3 — Dokumen
            'cv_file'            => 'nullable|mimes:pdf|max:5120',
            'sertifikat_file'    => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120',
            'link_portfolio'     => 'nullable|url|max:500',
        ]);

        // Cek NIM sudah terdaftar di rekrutmen yang sama
        $existing = Pendaftar::where('rekrutmen_id', $rekrutmen->id)
            ->where('nim', $validated['nim'])
            ->exists();

        if ($existing) {
            return back()->withInput()
                ->with('error', 'NIM sudah terdaftar pada rekrutmen ini.');
        }

        // Handle file uploads
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pendaftar/foto', 'public');
        }
        if ($request->hasFile('cv_file')) {
            $validated['cv_file'] = $request->file('cv_file')->store('pendaftar/cv', 'public');
        }
        if ($request->hasFile('sertifikat_file')) {
            $validated['sertifikat_file'] = $request->file('sertifikat_file')->store('pendaftar/sertifikat', 'public');
        }

        $validated['rekrutmen_id'] = $rekrutmen->id;
        $validated['status'] = 'mendaftar';

        $pendaftar = Pendaftar::create($validated);

        return redirect()->route('pendaftaran.success', [
            'slug' => $rekrutmen->slug,
            'kode' => $pendaftar->kode_pendaftaran,
        ])->with('success', 'Pendaftaran berhasil!');
    }

    /**
     * Success page after registration.
     */
    public function success(string $slug, Request $request)
    {
        $rekrutmen = Rekrutmen::where('slug', $slug)->firstOrFail();
        $kode = $request->query('kode');

        return view('pendaftaran.success', compact('rekrutmen', 'kode'));
    }
}
