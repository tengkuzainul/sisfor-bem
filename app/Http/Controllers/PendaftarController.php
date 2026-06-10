<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Pendaftar;
use App\Models\PendaftarReview;
use App\Models\Rekrutmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendaftarController extends Controller
{
    /**
     * List applicants with filters.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Pendaftar::with(['rekrutmen', 'departemenPilihan1', 'departemenPilihan2'])
                ->withCount('reviews');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%")
                      ->orWhere('kode_pendaftaran', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($status = $request->input('status_filter')) {
                $query->where('status', $status);
            }

            if ($rekrutmenId = $request->input('rekrutmen_id')) {
                $query->where('rekrutmen_id', $rekrutmenId);
            }

            if ($deptId = $request->input('departemen_id')) {
                $query->where(function ($q) use ($deptId) {
                    $q->where('departemen_pilihan_1', $deptId)
                      ->orWhere('departemen_pilihan_2', $deptId);
                });
            }

            $sortBy  = $request->input('sort_by', 'created_at');
            $sortDir = $request->input('sort_dir', 'desc');
            $query->orderBy($sortBy, $sortDir);

            return response()->json(
                $query->paginate($request->input('per_page', 10))
            );
        }

        $rekrutmenList = Rekrutmen::orderByDesc('created_at')->get(['id', 'judul']);
        $departemenList = Departemen::orderBy('nama')->get(['id', 'nama']);

        return view('pendaftar.index', compact('rekrutmenList', 'departemenList'));
    }

    /**
     * Show applicant detail with review history.
     */
    public function show(Pendaftar $pendaftar)
    {
        $pendaftar->load([
            'rekrutmen',
            'departemenPilihan1',
            'departemenPilihan2',
            'reviews.reviewer',
            'reviews.departemen',
        ]);

        $departemenList = Departemen::orderBy('nama')->get(['id', 'nama']);

        return view('pendaftar.show', compact('pendaftar', 'departemenList'));
    }

    /**
     * Submit a review for an applicant.
     */
    public function review(Request $request, Pendaftar $pendaftar)
    {
        $validated = $request->validate([
            'tipe'               => 'required|in:saran,kritik,rekomendasi',
            'komentar'           => 'required|string|min:10',
            'departemen_id'      => 'nullable|exists:departemen,id',
            'rekomendasi_status' => 'nullable|required_if:tipe,rekomendasi|in:direkomendasikan,tidak_direkomendasikan,netral',
        ]);

        $validated['pendaftar_id'] = $pendaftar->id;
        $validated['user_id'] = Auth::id();

        PendaftarReview::create($validated);

        return back()->with('success', 'Review berhasil ditambahkan.');
    }

    /**
     * Update applicant status.
     */
    public function updateStatus(Request $request, Pendaftar $pendaftar)
    {
        $request->validate([
            'status'       => 'required|in:mendaftar,review,wawancara,diterima,ditolak,cadangan',
            'catatan_admin' => 'nullable|string',
        ]);

        $pendaftar->update([
            'status'       => $request->status,
            'catatan_admin' => $request->catatan_admin ?? $pendaftar->catatan_admin,
        ]);

        $msg = "Status pendaftar berhasil diubah menjadi \"{$pendaftar->status_label}\".";
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : back()->with('success', $msg);
    }
}
