<?php

namespace App\Http\Controllers;

use App\Models\Kepengurusan;
use App\Models\ProgramKerja;
use App\Models\ProposalKegiatan;
use App\Models\ProposalReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    /**
     * Index — all proposals (filtered by role).
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($request->ajax()) {
            $query = ProposalKegiatan::with(['programKerja', 'pengaju']);

            // Pengurus only sees their own
            if ($user->isPengurus()) {
                $query->where('user_id', $user->id);
            }
            // Pembina sees proposals that are at pembina stage (or past it)
            if ($user->isPembina()) {
                $query->whereNotIn('status', ['draft']);
            }

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                        ->orWhereHas('programKerja', fn ($q2) => $q2->where('nama', 'like', "%{$search}%"))
                        ->orWhereHas('pengaju', fn ($q2) => $q2->where('name', 'like', "%{$search}%"));
                });
            }

            if ($status = $request->input('status')) {
                $query->where('status', $status);
            }

            $sortBy = $request->input('sort_by', 'created_at');
            $sortDir = $request->input('sort_dir', 'desc');
            $query->orderBy($sortBy, $sortDir);

            $paginated = $query->paginate($request->input('per_page', 10));
            $paginated->getCollection()->transform(function ($p) {
                $p->append(['status_label', 'status_color', 'step_label']);

                return $p;
            });

            return response()->json($paginated);
        }

        return view('proposal.index');
    }

    /**
     * Form to create (pengurus only).
     */
    public function create()
    {
        $activeKepengurusan = Kepengurusan::getActive();
        $programKerjaList = $activeKepengurusan
            ? ProgramKerja::where('kepengurusan_id', $activeKepengurusan->id)->orderBy('nama')->get()
            : collect();

        return view('proposal.create', compact('programKerjaList'));
    }

    /**
     * Store new proposal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_kerja_id' => 'required|exists:program_kerja,id',
            'judul' => 'required|string|max:255',
            'file_proposal' => 'required|file|mimes:pdf|max:10240',
            'catatan_pengaju' => 'nullable|string',
        ]);

        $path = $request->file('file_proposal')->store('proposal', 'public');

        $proposal = ProposalKegiatan::create([
            'program_kerja_id' => $request->program_kerja_id,
            'user_id' => $request->user()->id,
            'judul' => $request->judul,
            'file_proposal' => $path,
            'catatan_pengaju' => $request->catatan_pengaju,
            'status' => ProposalKegiatan::STATUS_DIAJUKAN,
        ]);

        return redirect()->route('proposal.show', $proposal)
            ->with('success', 'Proposal berhasil diajukan.');
    }

    /**
     * Show detail + review timeline.
     */
    public function show(ProposalKegiatan $proposal)
    {
        $proposal->load(['programKerja', 'pengaju', 'reviews.reviewer']);

        return view('proposal.show', compact('proposal'));
    }

    /**
     * Pengurus re-uploads after revision.
     */
    public function revise(Request $request, ProposalKegiatan $proposal)
    {
        $user = $request->user();

        // Only pengaju can revise their own proposal
        if ($proposal->user_id !== $user->id || ! $proposal->canRevise()) {
            abort(403);
        }

        $request->validate([
            'file_proposal' => 'required|file|mimes:pdf|max:10240',
            'catatan' => 'nullable|string',
        ]);

        // Delete old file
        if ($proposal->file_proposal) {
            Storage::disk('public')->delete($proposal->file_proposal);
        }

        $path = $request->file('file_proposal')->store('proposal', 'public');
        $proposal->file_proposal = $path;

        // Move back to review stage
        if ($proposal->status === ProposalKegiatan::STATUS_REVISI_PEMBINA) {
            $proposal->status = ProposalKegiatan::STATUS_REVIEW_PEMBINA;
        }
        $proposal->save();

        // Log the revision upload
        ProposalReview::create([
            'proposal_kegiatan_id' => $proposal->id,
            'user_id' => $user->id,
            'aksi' => 'revisi',
            'komentar' => $request->catatan ?? 'Mengupload ulang proposal setelah revisi.',
            'file_lampiran' => $path,
        ]);

        return redirect()->route('proposal.show', $proposal)
            ->with('success', 'Proposal berhasil diupload ulang.');
    }

    /**
     * Pembina review action.
     */
    public function review(Request $request, ProposalKegiatan $proposal)
    {
        $user = $request->user();

        $request->validate([
            'aksi' => 'required|in:komentar,revisi,approve,tolak',
            'komentar' => 'nullable|required_if:aksi,revisi,tolak,komentar|string',
        ]);

        $aksi = $request->aksi;

        // Validate reviewer permissions
        if ($user->isPembina()) {
            if (! $proposal->canReviewByPembina()) {
                abort(403, 'Proposal tidak dalam tahap review pembina.');
            }
        } elseif (! $user->isAdmin()) {
            abort(403);
        }

        // Create review log
        ProposalReview::create([
            'proposal_kegiatan_id' => $proposal->id,
            'user_id' => $user->id,
            'aksi' => $aksi,
            'komentar' => $request->komentar,
        ]);

        // Update proposal status based on action
        if ($user->isPembina() || ($user->isAdmin() && $proposal->canReviewByPembina())) {
            switch ($aksi) {
                case 'approve':
                    $proposal->status = ProposalKegiatan::STATUS_DISETUJUI;
                    break;
                case 'revisi':
                    $proposal->status = ProposalKegiatan::STATUS_REVISI_PEMBINA;
                    break;
                case 'tolak':
                    $proposal->status = ProposalKegiatan::STATUS_DITOLAK;
                    break;
            }
        }

        $proposal->save();

        $messages = [
            'komentar' => 'Komentar berhasil ditambahkan.',
            'revisi' => 'Proposal diminta untuk direvisi.',
            'approve' => 'Proposal berhasil disetujui.',
            'tolak' => 'Proposal berhasil ditolak.',
        ];

        return redirect()->route('proposal.show', $proposal)
            ->with('success', $messages[$aksi] ?? 'Aksi berhasil.');
    }
}
