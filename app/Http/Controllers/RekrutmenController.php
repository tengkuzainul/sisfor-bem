<?php

namespace App\Http\Controllers;

use App\Models\Rekrutmen;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RekrutmenController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Rekrutmen::with('kepengurusan')
                ->withCount('pendaftar');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            if ($status = $request->input('status_filter')) {
                $query->where('status', $status);
            }

            $sortBy  = $request->input('sort_by', 'created_at');
            $sortDir = $request->input('sort_dir', 'desc');
            $query->orderBy($sortBy, $sortDir);

            return response()->json(
                $query->paginate($request->input('per_page', 10))
            );
        }

        return view('rekrutmen.index');
    }

    public function create()
    {
        $kepengurusan = Kepengurusan::orderByDesc('tanggal_mulai')->get();
        return view('rekrutmen.create', compact('kepengurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kepengurusan_id'  => 'required|exists:kepengurusan,id',
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'persyaratan'      => 'nullable|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'poster'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'           => 'required|in:draft,dibuka,ditutup,selesai',
        ]);

        if ($request->hasFile('poster')) {
            $validated['poster'] = $request->file('poster')->store('rekrutmen/poster', 'public');
        }

        Rekrutmen::create($validated);

        return redirect()->route('rekrutmen.index')
            ->with('success', 'Data rekrutmen berhasil ditambahkan.');
    }

    public function show(Rekrutmen $rekrutmen)
    {
        $rekrutmen->load(['kepengurusan', 'pendaftar.departemenPilihan1', 'pendaftar.departemenPilihan2']);

        $stats = [
            'total'     => $rekrutmen->pendaftar->count(),
            'mendaftar' => $rekrutmen->pendaftar->where('status', 'mendaftar')->count(),
            'review'    => $rekrutmen->pendaftar->where('status', 'review')->count(),
            'wawancara' => $rekrutmen->pendaftar->where('status', 'wawancara')->count(),
            'diterima'  => $rekrutmen->pendaftar->where('status', 'diterima')->count(),
            'ditolak'   => $rekrutmen->pendaftar->where('status', 'ditolak')->count(),
            'cadangan'  => $rekrutmen->pendaftar->where('status', 'cadangan')->count(),
        ];

        return view('rekrutmen.show', compact('rekrutmen', 'stats'));
    }

    public function edit(Rekrutmen $rekrutmen)
    {
        $kepengurusan = Kepengurusan::orderByDesc('tanggal_mulai')->get();
        return view('rekrutmen.edit', compact('rekrutmen', 'kepengurusan'));
    }

    public function update(Request $request, Rekrutmen $rekrutmen)
    {
        $validated = $request->validate([
            'kepengurusan_id'  => 'required|exists:kepengurusan,id',
            'judul'            => 'required|string|max:255',
            'deskripsi'        => 'nullable|string',
            'persyaratan'      => 'nullable|string',
            'tanggal_mulai'    => 'required|date',
            'tanggal_berakhir' => 'required|date|after:tanggal_mulai',
            'poster'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status'           => 'required|in:draft,dibuka,ditutup,selesai',
        ]);

        if ($request->hasFile('poster')) {
            // Delete old poster
            if ($rekrutmen->poster) {
                Storage::disk('public')->delete($rekrutmen->poster);
            }
            $validated['poster'] = $request->file('poster')->store('rekrutmen/poster', 'public');
        }

        $rekrutmen->update($validated);

        return redirect()->route('rekrutmen.index')
            ->with('success', 'Data rekrutmen berhasil diperbarui.');
    }

    public function destroy(Rekrutmen $rekrutmen)
    {
        if ($rekrutmen->pendaftar()->count() > 0) {
            $msg = 'Rekrutmen tidak dapat dihapus karena masih memiliki pendaftar.';
            return request()->ajax()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        if ($rekrutmen->poster) {
            Storage::disk('public')->delete($rekrutmen->poster);
        }

        $rekrutmen->delete();

        $msg = 'Data rekrutmen berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('rekrutmen.index')->with('success', $msg);
    }

    public function updateStatus(Request $request, Rekrutmen $rekrutmen)
    {
        $request->validate([
            'status' => 'required|in:draft,dibuka,ditutup,selesai',
        ]);

        $rekrutmen->update(['status' => $request->status]);

        $msg = "Status rekrutmen berhasil diubah menjadi \"{$rekrutmen->status_label}\".";
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : back()->with('success', $msg);
    }
}
