<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Target;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $ustadz = Auth::user()->ustadz;

        // Halaqah yang diampu ustadz ini
        $halaqah = $ustadz->halaqah()->get();
        $halaqahIds = $halaqah->pluck('id');

        // Semua santri aktif di halaqah ustadz
        $santriList = Santri::active()
            ->whereIn('halaqah_id', $halaqahIds)
            ->with('halaqah')
            ->orderBy('nama_lengkap')
            ->get();

        $santriIds = $santriList->pluck('id');
        $totalSantri = $santriList->count();

        // 5 setoran terbaru dari santri di halaqah ustadz
        $setoranTerbaru = Setoran::with(['santri', 'surah', 'evaluasi'])
            ->whereIn('santri_id', $santriIds)
            ->orderByDesc('tanggal_setoran')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Santri yang tidak ada setoran dalam 7 hari terakhir
        $batas7Hari = Carbon::today()->subDays(7);

        $santriYangSetor = Setoran::whereIn('santri_id', $santriIds)
            ->where('tanggal_setoran', '>=', $batas7Hari)
            ->pluck('santri_id')
            ->unique();

        $santriTidakSetor = $santriList->whereNotIn('id', $santriYangSetor)->values();

        // Tambahkan info terakhir setor untuk setiap santri tidak setor
        foreach ($santriTidakSetor as $santri) {
            $santri->terakhir_setor = Setoran::where('santri_id', $santri->id)
                ->orderByDesc('tanggal_setoran')
                ->value('tanggal_setoran');
        }

        // Rata-rata nilai_akhir dari evaluasi santri di halaqah (bulan ini)
        $rataRataNilai = DB::table('evaluasi')
            ->join('setoran', 'evaluasi.setoran_id', '=', 'setoran.id')
            ->whereIn('setoran.santri_id', $santriIds)
            ->whereMonth('setoran.tanggal_setoran', now()->month)
            ->whereYear('setoran.tanggal_setoran', now()->year)
            ->avg('evaluasi.nilai_akhir');

        $rataRataNilai = $rataRataNilai ? round($rataRataNilai, 1) : null;

        // Target aktif: belum tercapai dan batas waktu belum lewat
        $targetAktif = Target::whereIn('santri_id', $santriIds)
            ->where('is_tercapai', false)
            ->where('batas_waktu', '>=', Carbon::today())
            ->count();

        // Target terlewat: batas waktu sudah lewat dan belum tercapai
        $targetTerlewat = Target::whereIn('santri_id', $santriIds)
            ->where('is_tercapai', false)
            ->where('batas_waktu', '<', Carbon::today())
            ->count();

        return view('ustadz.dashboard', compact(
            'ustadz',
            'halaqah',
            'santriList',
            'totalSantri',
            'setoranTerbaru',
            'santriTidakSetor',
            'rataRataNilai',
            'targetAktif',
            'targetTerlewat',
        ));
    }
}
