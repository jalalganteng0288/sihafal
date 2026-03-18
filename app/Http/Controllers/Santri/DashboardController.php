<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use App\Services\HafalanService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(private HafalanService $hafalanService) {}

    public function index()
    {
        $santri = Auth::user()->santri()->with('halaqah')->first();

        $totalHafalan = $this->hafalanService->hitungTotalHafalan($santri->id);

        $persenJuz = min(($totalHafalan['total_juz'] / 30) * 100, 100);

        $setoranTerbaru = $santri->setoran()
            ->with(['surah', 'evaluasi'])
            ->orderByDesc('tanggal_setoran')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Evaluasi terbaru dari setoran yang punya evaluasi
        $nilaiTerbaru = $setoranTerbaru
            ->filter(fn ($s) => $s->evaluasi !== null)
            ->first()
            ?->evaluasi;

        $targetAktif = $santri->target()
            ->where('is_tercapai', false)
            ->where('batas_waktu', '>=', Carbon::today())
            ->orderBy('batas_waktu')
            ->get();

        // Data chart: 10 setoran terbaru yang punya evaluasi
        $setoranDenganEvaluasi = $setoranTerbaru
            ->filter(fn ($s) => $s->evaluasi !== null)
            ->values();

        $chartLabels = $setoranDenganEvaluasi
            ->map(fn ($s) => $s->tanggal_setoran->format('d/m/Y'))
            ->toArray();

        $chartData = $setoranDenganEvaluasi
            ->map(fn ($s) => (float) $s->evaluasi->nilai_akhir)
            ->toArray();

        return view('santri.dashboard', compact(
            'santri',
            'totalHafalan',
            'persenJuz',
            'setoranTerbaru',
            'nilaiTerbaru',
            'targetAktif',
            'chartLabels',
            'chartData',
        ));
    }
}
