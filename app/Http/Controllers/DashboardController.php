<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Departemen;
use App\Models\Kepengurusan;
use App\Models\Keanggotaan;
use App\Models\ProgramKerja;
use App\Models\ProposalKegiatan;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $kepengurusan = Kepengurusan::getActive();

        $totalAnggota = 0;
        $kegiatanAktif = 0;
        $totalProgramKerja = 0;
        $totalProposals = 0;
        $chartLabels = [];
        $chartValues = [];
        $progressItems = [];
        $upcomingEvents = collect();
        $departemenStats = collect();
        $recentActivities = collect();

        if ($kepengurusan) {
            /** @var \Illuminate\Database\Eloquent\Builder $programQuery */
            $programQuery = ProgramKerja::query()->where('kepengurusan_id', $kepengurusan->id);

            $totalProgramKerja = (clone $programQuery)->count('*');
            $kegiatanAktif = (clone $programQuery)->where('status', 'berlangsung')->count('*');
            $totalProposals = ProposalKegiatan::query()
                ->whereHas('programKerja', function ($query) use ($kepengurusan) {
                    $query->where('kepengurusan_id', $kepengurusan->id);
                })
                ->count('*');
            $totalAnggota = Keanggotaan::query()
                ->where('kepengurusan_id', $kepengurusan->id)
                ->where('status', 'aktif')
                ->distinct()
                ->count('anggota_id');

            $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $chartLabels = [];
            $chartValues = [];

            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $chartLabels[] = $monthLabels[$month->month - 1];
                $chartValues[] = Keanggotaan::query()
                    ->where('kepengurusan_id', $kepengurusan->id)
                    ->whereRaw('MONTH(created_at) = ?', [$month->month])
                    ->whereRaw('YEAR(created_at) = ?', [$month->year])
                    ->count('*');
            }

            $statusCounts = (clone $programQuery)
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $progressItems = [
                [
                    'name' => 'Selesai',
                    'progress' => $totalProgramKerja ? round((($statusCounts['selesai'] ?? 0) / $totalProgramKerja) * 100) : 0,
                    'textClass' => 'text-success-600 dark:text-success-400',
                    'barClass' => 'from-success-500 to-success-400',
                    'count' => $statusCounts['selesai'] ?? 0,
                ],
                [
                    'name' => 'Berlangsung',
                    'progress' => $totalProgramKerja ? round((($statusCounts['berlangsung'] ?? 0) / $totalProgramKerja) * 100) : 0,
                    'textClass' => 'text-primary-600 dark:text-primary-400',
                    'barClass' => 'from-primary-500 to-primary-400',
                    'count' => $statusCounts['berlangsung'] ?? 0,
                ],
                [
                    'name' => 'Pending',
                    'progress' => $totalProgramKerja ? round((($statusCounts['pending'] ?? 0) / $totalProgramKerja) * 100) : 0,
                    'textClass' => 'text-warning-600 dark:text-warning-400',
                    'barClass' => 'from-warning-500 to-warning-400',
                    'count' => $statusCounts['pending'] ?? 0,
                ],
                [
                    'name' => 'Coming Soon',
                    'progress' => $totalProgramKerja ? round((($statusCounts['coming_soon'] ?? 0) / $totalProgramKerja) * 100) : 0,
                    'textClass' => 'text-accent-600 dark:text-accent-400',
                    'barClass' => 'from-accent-500 to-accent-400',
                    'count' => $statusCounts['coming_soon'] ?? 0,
                ],
            ];

            $upcomingEvents = (clone $programQuery)
                ->with(['kategori'])
                ->whereIn('status', ['coming_soon', 'berlangsung'])
                ->whereNotNull('tanggal_mulai')
                ->whereDate('tanggal_mulai', '>=', Carbon::today())
                ->orderBy('tanggal_mulai')
                ->limit(5)
                ->get()
                ->map(function ($event) {
                    $badgeClass = match ($event->status) {
                        'berlangsung' => 'bg-success-100 text-success-700 dark:bg-success-900/40 dark:text-success-400',
                        'pending' => 'bg-warning-100 text-warning-700 dark:bg-warning-900/40 dark:text-warning-400',
                        'coming_soon' => 'bg-accent-100 text-accent-700 dark:bg-accent-900/40 dark:text-accent-400',
                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400',
                    };

                    return [
                        'title' => $event->nama,
                        'date' => $event->tanggal_mulai?->format('d M Y') ?? '-',
                        'time' => $event->tanggal_selesai ? 'Sampai ' . $event->tanggal_selesai->format('d M Y') : '-',
                        'location' => $event->lokasi ?? '-',
                        'type' => $event->kategori?->nama ?? ucfirst(str_replace('_', ' ', $event->status)),
                        'badgeClass' => $badgeClass,
                        'icon' => 'calendar-days',
                    ];
                });

            $departemenStats = Departemen::query()
                ->where('kepengurusan_id', $kepengurusan->id)
                ->with(['keanggotaan' => function ($query) {
                    $query->where('status', 'aktif')->with(['anggota', 'jabatan']);
                }])
                ->get()
                ->map(function ($departemen) {
                    $activeMembers = $departemen->keanggotaan;
                    $leader = $activeMembers->sortBy(fn ($item) => $item->jabatan?->level ?? 99)->first();
                    $totalProker = ProgramKerja::query()->where('departemen_id', $departemen->id)->count('*');
                    $finishedProker = ProgramKerja::query()->where('departemen_id', $departemen->id)
                        ->where('status', 'selesai')
                        ->count('*');

                    return [
                        'nama' => $departemen->nama,
                        'ketua' => $leader?->anggota?->nama ?? '-',
                        'jumlahAnggota' => $activeMembers->count(),
                        'prokerSelesai' => $finishedProker,
                        'totalProker' => $totalProker,
                        'status' => $activeMembers->count() > 0 ? 'active' : 'inactive',
                    ];
                });

            $recentActivities = ProposalKegiatan::with(['pengaju', 'programKerja'])
                ->orderByDesc('created_at')
                ->limit(5)
                ->get()
                ->map(function ($proposal) {
                    return [
                        'user' => $proposal->pengaju?->name ?? 'Pengguna',
                        'action' => 'mengajukan proposal "' . $proposal->judul . '"',
                        'time' => $proposal->created_at?->diffForHumans() ?? '-',
                        'icon' => 'document-text',
                        'bgClass' => 'bg-accent-100 dark:bg-accent-900/40',
                        'iconClass' => 'text-accent-600 dark:text-accent-400',
                    ];
                });
        }

        return view('dashboard.index', compact(
            'kepengurusan',
            'totalAnggota',
            'kegiatanAktif',
            'totalProgramKerja',
            'totalProposals',
            'chartLabels',
            'chartValues',
            'progressItems',
            'upcomingEvents',
            'departemenStats',
            'recentActivities'
        ));
    }
}
