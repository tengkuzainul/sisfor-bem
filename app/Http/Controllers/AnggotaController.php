<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Departemen;
use App\Models\Jabatan;
use App\Models\Keanggotaan;
use App\Models\Kepengurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $activeKepengurusan = Kepengurusan::getActive();

        if ($request->ajax()) {
            $query = Anggota::with([
                'keanggotaan' => fn ($q) => $q->when(
                    $activeKepengurusan,
                    fn ($q2) => $q2->where('kepengurusan_id', $activeKepengurusan->id)
                )->with(['departemen', 'jabatan']),
            ]);

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('prodi', 'like', "%{$search}%");
                });
            }

            $sortBy  = $request->input('sort_by', 'nama');
            $sortDir = $request->input('sort_dir', 'asc');
            $query->orderBy($sortBy, $sortDir);

            $paginated = $query->paginate($request->input('per_page', 10));

            // Append computed attributes & hide raw keanggotaan array
            $paginated->getCollection()->transform(function ($anggota) {
                $anggota->append(['inisial', 'active_keanggotaan']);
                $anggota->makeHidden('keanggotaan');
                return $anggota;
            });

            return response()->json($paginated);
        }

        return view('anggota.index', compact('activeKepengurusan'));
    }

    public function create()
    {
        $activeKepengurusan = Kepengurusan::getActive();
        $departemenList     = $activeKepengurusan
            ? Cache::remember('departemen_list_' . $activeKepengurusan->id, 3600, function () use ($activeKepengurusan) {
                return Departemen::where('kepengurusan_id', $activeKepengurusan->id)->orderBy('nama')->get();
            })
            : collect();
        $jabatanList = Cache::remember('jabatan_list', 3600, function () {
            return Jabatan::ordered()->get();
        });

        return view('anggota.create', compact('activeKepengurusan', 'departemenList', 'jabatanList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'nim'               => 'required|string|max:20|unique:anggota,nim',
            'email'             => 'nullable|email|max:255',
            'no_hp'             => 'nullable|string|max:20',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'angkatan'          => 'nullable|string|max:4',
            'prodi'             => 'nullable|string|max:255',
            'alamat'            => 'nullable|string',
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'departemen_id'     => 'nullable|exists:departemen,id',
            'jabatan_id'        => 'required|exists:jabatan,id',
            'kepengurusan_id'   => 'required|exists:kepengurusan,id',
        ]);

        $anggotaData = $request->only([
            'nama', 'nim', 'email', 'no_hp', 'jenis_kelamin', 'angkatan', 'prodi', 'alamat',
        ]);

        if ($request->hasFile('foto')) {
            $anggotaData['foto'] = $request->file('foto')->store('anggota/foto', 'public');
        }

        $anggota = Anggota::create($anggotaData);

        // Buat keanggotaan di kepengurusan aktif
        Keanggotaan::create([
            'kepengurusan_id'   => $validated['kepengurusan_id'],
            'anggota_id'        => $anggota->id,
            'departemen_id'     => $validated['departemen_id'] ?? null,
            'jabatan_id'        => $validated['jabatan_id'],
            'status'            => 'aktif',
            'tanggal_bergabung' => now(),
        ]);

        Kepengurusan::flushCache();

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(Anggota $anggota)
    {
        $activeKepengurusan = Kepengurusan::getActive();
        $departemenList     = $activeKepengurusan
            ? Cache::remember('departemen_list_' . $activeKepengurusan->id, 3600, function () use ($activeKepengurusan) {
                return Departemen::where('kepengurusan_id', $activeKepengurusan->id)->orderBy('nama')->get();
            })
            : collect();
        $jabatanList = Cache::remember('jabatan_list', 3600, function () {
            return Jabatan::ordered()->get();
        });

        // Ambil keanggotaan di kepengurusan aktif
        $keanggotaan = $anggota->keanggotaan()
            ->when($activeKepengurusan, fn ($q) => $q->where('kepengurusan_id', $activeKepengurusan->id))
            ->first();

        return view('anggota.edit', compact('anggota', 'activeKepengurusan', 'departemenList', 'jabatanList', 'keanggotaan'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama'              => 'required|string|max:255',
            'nim'               => 'required|string|max:20|unique:anggota,nim,' . $anggota->id,
            'email'             => 'nullable|email|max:255',
            'no_hp'             => 'nullable|string|max:20',
            'jenis_kelamin'     => 'nullable|in:L,P',
            'angkatan'          => 'nullable|string|max:4',
            'prodi'             => 'nullable|string|max:255',
            'alamat'            => 'nullable|string',
            'foto'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'departemen_id'     => 'nullable|exists:departemen,id',
            'jabatan_id'        => 'required|exists:jabatan,id',
            'kepengurusan_id'   => 'required|exists:kepengurusan,id',
        ]);

        $anggotaData = $request->only([
            'nama', 'nim', 'email', 'no_hp', 'jenis_kelamin', 'angkatan', 'prodi', 'alamat',
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $anggotaData['foto'] = $request->file('foto')->store('anggota/foto', 'public');
        } elseif ($request->boolean('hapus_foto')) {
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $anggotaData['foto'] = null;
        }

        $anggota->update($anggotaData);

        // Update atau buat keanggotaan
        $anggota->keanggotaan()->updateOrCreate(
            [
                'kepengurusan_id' => $validated['kepengurusan_id'],
                'anggota_id'      => $anggota->id,
            ],
            [
                'departemen_id' => $validated['departemen_id'] ?? null,
                'jabatan_id'    => $validated['jabatan_id'],
            ]
        );

        Kepengurusan::flushCache();

        return redirect()->route('anggota.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        if ($anggota->foto) {
            Storage::disk('public')->delete($anggota->foto);
        }
        $anggota->delete();
        Kepengurusan::flushCache();

        $msg = 'Anggota berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('anggota.index')->with('success', $msg);
    }
}
