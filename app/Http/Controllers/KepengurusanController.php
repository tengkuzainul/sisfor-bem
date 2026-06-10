<?php

namespace App\Http\Controllers;

use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KepengurusanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kepengurusan::withCount(['departemen', 'keanggotaan']);

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('periode', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            $sortBy  = $request->input('sort_by', 'tanggal_mulai');
            $sortDir = $request->input('sort_dir', 'desc');
            $query->orderByDesc('is_active')->orderBy($sortBy, $sortDir);

            return response()->json(
                $query->paginate($request->input('per_page', 10))
            );
        }

        return view('kepengurusan.index');
    }

    public function create()
    {
        return view('kepengurusan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'             => 'required|string|max:255',
            'periode'          => 'required|string|max:20',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after:tanggal_mulai',
            'visi'             => 'nullable|string',
            'misi'             => 'nullable|string',
            'deskripsi'        => 'nullable|string',
        ]);

        Kepengurusan::create($validated);
        Kepengurusan::flushCache();

        return redirect()->route('kepengurusan.index')
            ->with('success', 'Kepengurusan berhasil ditambahkan.');
    }

    public function show(Kepengurusan $kepengurusan)
    {
        $kepengurusan->load([
            'departemen.keanggotaan.anggota',
            'departemen.keanggotaan.jabatan',
        ]);

        return view('kepengurusan.show', compact('kepengurusan'));
    }

    public function edit(Kepengurusan $kepengurusan)
    {
        return view('kepengurusan.edit', compact('kepengurusan'));
    }

    public function update(Request $request, Kepengurusan $kepengurusan)
    {
        $validated = $request->validate([
            'nama'             => 'required|string|max:255',
            'periode'          => 'required|string|max:20',
            'tanggal_mulai'    => 'required|date',
            'tanggal_selesai'  => 'required|date|after:tanggal_mulai',
            'visi'             => 'nullable|string',
            'misi'             => 'nullable|string',
            'deskripsi'        => 'nullable|string',
        ]);

        $kepengurusan->update($validated);
        Kepengurusan::flushCache();

        return redirect()->route('kepengurusan.index')
            ->with('success', 'Kepengurusan berhasil diperbarui.');
    }

    public function destroy(Kepengurusan $kepengurusan)
    {
        if ($kepengurusan->is_active) {
            $msg = 'Kepengurusan aktif tidak dapat dihapus. Nonaktifkan terlebih dahulu.';
            return request()->ajax()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $kepengurusan->delete();
        Kepengurusan::flushCache();

        $msg = 'Kepengurusan berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('kepengurusan.index')->with('success', $msg);
    }

    /**
     * Aktifkan kepengurusan (hanya 1 yang aktif pada satu waktu).
     */
    public function activate(Kepengurusan $kepengurusan)
    {
        $kepengurusan->activate();

        $msg = "Kepengurusan \"{$kepengurusan->nama}\" berhasil diaktifkan.";
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : back()->with('success', $msg);
    }

    /**
     * Nonaktifkan kepengurusan.
     */
    public function deactivate(Kepengurusan $kepengurusan)
    {
        $kepengurusan->deactivate();

        $msg = "Kepengurusan \"{$kepengurusan->nama}\" berhasil dinonaktifkan.";
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : back()->with('success', $msg);
    }
}
