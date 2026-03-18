<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Halaqah;
use App\Models\Notifikasi;
use App\Models\Santri;
use App\Models\Setoran;
use App\Models\Ustadz;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalSantriAktif = Santri::where('is_active', true)->count();
        $totalUstadz      = Ustadz::count();
        $totalHalaqah     = Halaqah::count();
        $totalSetoran     = Setoran::whereMonth('tanggal_setoran', now()->month)
                                   ->whereYear('tanggal_setoran', now()->year)
                                   ->count();

        // 5 santri terbaru
        $santriTerbaru = Santri::with('halaqah')
                               ->orderByDesc('created_at')
                               ->limit(5)
                               ->get();

        // 5 setoran terbaru dengan relasi
        $setoranTerbaru = Setoran::with(['santri', 'surah', 'evaluasi'])
                                 ->orderByDesc('tanggal_setoran')
                                 ->orderByDesc('created_at')
                                 ->limit(5)
                                 ->get();

        // 5 notifikasi belum dibaca untuk admin yang login
        $notifikasiTerbaru = Notifikasi::where('user_id', Auth::id())
                                       ->belumDibaca()
                                       ->orderByDesc('created_at')
                                       ->limit(5)
                                       ->get();

        // Data grafik bulanan — 12 bulan terakhir
        $dataGrafikBulanan = $this->getDataGrafikBulanan();

        return view('admin.dashboard', compact(
            'totalSantriAktif',
            'totalUstadz',
            'totalHalaqah',
            'totalSetoran',
            'santriTerbaru',
            'setoranTerbaru',
            'notifikasiTerbaru',
            'dataGrafikBulanan',
        ));
    }

    private function getDataGrafikBulanan(): array
    {
        $labels = [];
        $data   = [];

        for ($i = 11; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $labels[] = $bulan->translatedFormat('M Y');

            $jumlah = Setoran::whereYear('tanggal_setoran', $bulan->year)
                             ->whereMonth('tanggal_setoran', $bulan->month)
                             ->count();
            $data[] = $jumlah;
        }

        return compact('labels', 'data');
    }
}
