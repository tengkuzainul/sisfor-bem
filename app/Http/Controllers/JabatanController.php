<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Jabatan::withCount('keanggotaan');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            $sortBy  = $request->input('sort_by', 'level');
            $sortDir = $request->input('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);

            return response()->json(
                $query->paginate($request->input('per_page', 10))
            );
        }

        return view('jabatan.index');
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'level'     => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Jabatan::create($validated);
        Kepengurusan::flushCache();

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama'      => 'required|string|max:255',
            'level'     => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $jabatan->update($validated);
        Kepengurusan::flushCache();

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->keanggotaan()->exists()) {
            $msg = 'Jabatan sedang digunakan, tidak bisa dihapus.';
            return request()->ajax()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $jabatan->delete();
        Kepengurusan::flushCache();

        $msg = 'Jabatan berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('jabatan.index')->with('success', $msg);
    }
}
