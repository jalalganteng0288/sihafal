<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Setoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller
{
    /**
     * Tampilkan daftar semua evaluasi santri di halaqah ustadz.
     */
    public function index(Request $request)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        $setoranList = Setoran::with(['santri', 'surah', 'evaluasi'])
            ->whereIn('santri_id', $santriIds)
            ->whereHas('evaluasi')
            ->orderByDesc('tanggal_setoran')
            ->paginate(15);

        return view('ustadz.evaluasi.index', compact('setoranList'));
    }

    /**
     * Tampilkan detail evaluasi satu setoran.
     */
    public function show(Setoran $setoran)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        abort_unless($santriIds->contains($setoran->santri_id), 403);

        $setoran->load(['santri', 'surah', 'evaluasi']);

        abort_unless($setoran->evaluasi !== null, 404);

        return view('ustadz.evaluasi.show', compact('setoran'));
    }

    /**
     * Tampilkan riwayat semua evaluasi santri tertentu.
     */
    public function riwayat(Santri $santri)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        abort_unless($santriIds->contains($santri->id), 403);

        $setoranList = Setoran::with(['surah', 'evaluasi'])
            ->where('santri_id', $santri->id)
            ->whereHas('evaluasi')
            ->orderBy('tanggal_setoran')
            ->get();

        // Data untuk Chart.js
        $chartLabels = $setoranList->map(fn ($s) =>
            $s->tanggal_setoran->format('d/m/Y') . ' - ' . $s->surah->nama_latin
        )->values();

        $chartData = $setoranList->map(fn ($s) => $s->evaluasi->nilai_akhir)->values();

        return view('ustadz.evaluasi.riwayat', compact('santri', 'setoranList', 'chartLabels', 'chartData'));
    }
}
