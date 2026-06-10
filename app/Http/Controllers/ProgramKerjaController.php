<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\DokumentasiProker;
use App\Models\KategoriProker;
use App\Models\Kepengurusan;
use App\Models\ProgramKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProgramKerjaController extends Controller
{
    public function index(Request $request)
    {
        $activeKepengurusan = Kepengurusan::getActive();

        if ($request->ajax()) {
            $query = ProgramKerja::with(['kategori', 'departemen', 'kepengurusan'])
                ->withCount('dokumentasi')
                ->when($activeKepengurusan, fn ($q) => $q->where('kepengurusan_id', $activeKepengurusan->id));

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%")
                      ->orWhereHas('kategori', fn ($q2) => $q2->where('nama', 'like', "%{$search}%"));
                });
            }

            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            if ($kategori = $request->input('kategori')) {
                $query->where('kategori_proker_id', $kategori);
            }

            $sortBy  = $request->input('sort_by', 'tanggal_mulai');
            $sortDir = $request->input('sort_dir', 'desc');
            $query->orderBy($sortBy, $sortDir);

            $paginated = $query->paginate($request->input('per_page', 10));

            $paginated->getCollection()->transform(function ($proker) {
                $proker->append(['status_label', 'status_color']);
                return $proker;
            });

            return response()->json($paginated);
        }

        $kategoriList = KategoriProker::orderBy('nama')->get();

        return view('program-kerja.index', compact('activeKepengurusan', 'kategoriList'));
    }

    public function create()
    {
        $activeKepengurusan = Kepengurusan::getActive();
        $kategoriList = KategoriProker::orderBy('nama')->get();
        $departemenList = $activeKepengurusan
            ? Departemen::where('kepengurusan_id', $activeKepengurusan->id)->orderBy('nama')->get()
            : collect();

        return view('program-kerja.create', compact('activeKepengurusan', 'kategoriList', 'departemenList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'kepengurusan_id'    => 'required|exists:kepengurusan,id',
            'kategori_proker_id' => 'nullable|exists:kategori_proker,id',
            'departemen_id'      => 'nullable|exists:departemen,id',
            'deskripsi'          => 'nullable|string',
            'lokasi'             => 'nullable|string|max:255',
            'tanggal_mulai'      => 'nullable|date',
            'tanggal_selesai'    => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'             => 'required|in:coming_soon,berlangsung,pending,selesai',
            'catatan'            => 'nullable|string',
            'dokumentasi.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $proker = ProgramKerja::create($request->only([
            'nama', 'kepengurusan_id', 'kategori_proker_id', 'departemen_id',
            'deskripsi', 'lokasi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'catatan',
        ]));

        // Save dokumentasi
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('proker/dokumentasi', 'public');
                DokumentasiProker::create([
                    'program_kerja_id' => $proker->id,
                    'file_path'        => $path,
                    'tipe'             => 'image',
                ]);
            }
        }

        return redirect()->route('program-kerja.index')
            ->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function show(ProgramKerja $programKerja)
    {
        $programKerja->load(['kategori', 'departemen', 'kepengurusan', 'dokumentasi']);
        $programKerja->append(['status_label', 'status_color']);

        return view('program-kerja.show', compact('programKerja'));
    }

    public function edit(ProgramKerja $programKerja)
    {
        $programKerja->load('dokumentasi');
        $activeKepengurusan = Kepengurusan::getActive();
        $kategoriList = KategoriProker::orderBy('nama')->get();
        $departemenList = $activeKepengurusan
            ? Departemen::where('kepengurusan_id', $activeKepengurusan->id)->orderBy('nama')->get()
            : collect();

        return view('program-kerja.edit', compact('programKerja', 'activeKepengurusan', 'kategoriList', 'departemenList'));
    }

    public function update(Request $request, ProgramKerja $programKerja)
    {
        $validated = $request->validate([
            'nama'               => 'required|string|max:255',
            'kepengurusan_id'    => 'required|exists:kepengurusan,id',
            'kategori_proker_id' => 'nullable|exists:kategori_proker,id',
            'departemen_id'      => 'nullable|exists:departemen,id',
            'deskripsi'          => 'nullable|string',
            'lokasi'             => 'nullable|string|max:255',
            'tanggal_mulai'      => 'nullable|date',
            'tanggal_selesai'    => 'nullable|date|after_or_equal:tanggal_mulai',
            'status'             => 'required|in:coming_soon,berlangsung,pending,selesai',
            'catatan'            => 'nullable|string',
            'dokumentasi.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $programKerja->update($request->only([
            'nama', 'kepengurusan_id', 'kategori_proker_id', 'departemen_id',
            'deskripsi', 'lokasi', 'tanggal_mulai', 'tanggal_selesai', 'status', 'catatan',
        ]));

        // Delete selected dokumentasi
        if ($request->has('hapus_dokumentasi')) {
            $docs = DokumentasiProker::whereIn('id', $request->input('hapus_dokumentasi'))
                ->where('program_kerja_id', $programKerja->id)
                ->get();
            foreach ($docs as $doc) {
                Storage::disk('public')->delete($doc->file_path);
                $doc->delete();
            }
        }

        // Add new dokumentasi
        if ($request->hasFile('dokumentasi')) {
            foreach ($request->file('dokumentasi') as $file) {
                $path = $file->store('proker/dokumentasi', 'public');
                DokumentasiProker::create([
                    'program_kerja_id' => $programKerja->id,
                    'file_path'        => $path,
                    'tipe'             => 'image',
                ]);
            }
        }

        return redirect()->route('program-kerja.index')
            ->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy(ProgramKerja $programKerja)
    {
        // Delete all dokumentasi files
        foreach ($programKerja->dokumentasi as $doc) {
            Storage::disk('public')->delete($doc->file_path);
        }

        $programKerja->delete();

        $msg = 'Program kerja berhasil dihapus.';
        return request()->ajax()
            ? response()->json(['message' => $msg])
            : redirect()->route('program-kerja.index')->with('success', $msg);
    }

    /**
     * API: Get events for calendar (frontend).
     */
    public function calendarEvents(Request $request)
    {
        $activeKepengurusan = Kepengurusan::getActive();
        if (!$activeKepengurusan) {
            return response()->json([]);
        }

        $events = ProgramKerja::with(['kategori', 'departemen'])
            ->where('kepengurusan_id', $activeKepengurusan->id)
            ->get()
            ->map(function ($proker) {
                return [
                    'id'         => $proker->id,
                    'title'      => $proker->nama,
                    'start'      => $proker->tanggal_mulai?->format('Y-m-d'),
                    'end'        => $proker->tanggal_selesai?->format('Y-m-d') ?? $proker->tanggal_mulai?->format('Y-m-d'), // inclusive end
                    'color'      => $proker->kategori?->warna ?? '#6b7280',
                    'status'     => $proker->status,
                    'statusLabel' => $proker->status_label,
                    'kategori'   => $proker->kategori?->nama ?? '-',
                    'departemen' => $proker->departemen?->singkatan ?? $proker->departemen?->nama ?? 'BPH',
                    'lokasi'     => $proker->lokasi,
                    'url'        => route('home.proker.detail', $proker->id),
                ];
            });

        return response()->json($events);
    }
}
