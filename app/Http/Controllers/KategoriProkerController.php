<?php

namespace App\Http\Controllers;

use App\Models\KategoriProker;
use Illuminate\Http\Request;

class KategoriProkerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = KategoriProker::withCount('programKerja');

            if ($search = $request->input('search')) {
                $query->where('nama', 'like', "%{$search}%");
            }

            $sortBy = $request->input('sort_by', 'nama');
            $sortDir = $request->input('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);

            return response()->json(
                $query->paginate($request->input('per_page', 10))
            );
        }

        return view('kategori-proker.index');
    }

    public function create()
    {
        return view('kategori-proker.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'warna' => 'required|string|max:7',
            'deskripsi' => 'nullable|string',
        ]);

        KategoriProker::create($validated);

        return redirect()->route('kategori-proker.index')
            ->with('success', 'Kategori program kerja berhasil ditambahkan.');
    }

    public function edit(KategoriProker $kategoriProker)
    {
        return view('kategori-proker.edit', compact('kategoriProker'));
    }

    public function update(Request $request, KategoriProker $kategoriProker)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'warna' => 'required|string|max:7',
            'deskripsi' => 'nullable|string',
        ]);

        $kategoriProker->update($validated);

        return redirect()->route('kategori-proker.index')
            ->with('success', 'Kategori program kerja berhasil diperbarui.');
    }

    public function destroy(KategoriProker $kategoriProker)
    {
        $kategoriProker->delete();

        $msg = 'Kategori program kerja berhasil dihapus.';

        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('kategori-proker.index')->with('success', $msg);
    }
}
