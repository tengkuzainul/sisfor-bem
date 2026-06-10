<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DepartemenController extends Controller
{
    public function index(Request $request)
    {
        $activeKepengurusan = Kepengurusan::getActive();

        if ($request->ajax()) {
            $query = Departemen::with('kepengurusan')
                ->withCount('keanggotaan')
                ->when($activeKepengurusan, fn ($q) => $q->where('kepengurusan_id', $activeKepengurusan->id));

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('singkatan', 'like', "%{$search}%");
                });
            }

            $sortBy  = $request->input('sort_by', 'nama');
            $sortDir = $request->input('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);

            return response()->json(
                $query->paginate($request->input('per_page', 10))
            );
        }

        return view('departemen.index', compact('activeKepengurusan'));
    }

    public function create()
    {
        $kepengurusanList = Cache::remember('kepengurusan_list', 3600, function () {
            return Kepengurusan::orderByDesc('tanggal_mulai')->get();
        });
        return view('departemen.create', compact('kepengurusanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kepengurusan_id' => 'required|exists:kepengurusan,id',
            'nama'            => 'required|string|max:255',
            'singkatan'       => 'nullable|string|max:20',
            'deskripsi'       => 'nullable|string',
        ]);

        Departemen::create($validated);
        Kepengurusan::flushCache();
        Cache::forget('departemen_index_' . $validated['kepengurusan_id']);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function edit(Departemen $departemen)
    {
        $kepengurusanList = Cache::remember('kepengurusan_list', 3600, function () {
            return Kepengurusan::orderByDesc('tanggal_mulai')->get();
        });
        return view('departemen.edit', compact('departemen', 'kepengurusanList'));
    }

    public function update(Request $request, Departemen $departemen)
    {
        $validated = $request->validate([
            'kepengurusan_id' => 'required|exists:kepengurusan,id',
            'nama'            => 'required|string|max:255',
            'singkatan'       => 'nullable|string|max:20',
            'deskripsi'       => 'nullable|string',
        ]);

        $departemen->update($validated);
        Kepengurusan::flushCache();
        Cache::forget('departemen_index_' . $departemen->kepengurusan_id);

        return redirect()->route('departemen.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy(Departemen $departemen)
    {
        if ($departemen->keanggotaan()->exists()) {
            $msg = 'Departemen memiliki anggota, tidak bisa dihapus.';
            return request()->ajax()
                ? response()->json(['message' => $msg], 422)
                : back()->with('error', $msg);
        }

        $departemen->delete();
        Kepengurusan::flushCache();
        Cache::forget('departemen_index_' . $departemen->kepengurusan_id);

        $msg = 'Departemen berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('departemen.index')->with('success', $msg);
    }
}
