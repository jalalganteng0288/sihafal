<?php

namespace App\Services;

use App\Models\Halaqah;
use App\Models\Santri;

class LaporanService
{
    public function __construct(
        private readonly HafalanService $hafalanService
    ) {}

    /**
     * Generate laporan hafalan individu santri dalam periode tertentu.
     */
    public function generateLaporanIndividu(int $santriId, string $periodeAwal, string $periodeAkhir): array
    {
        $santri = Santri::findOrFail($santriId);

        $setoran = $santri->setoran()
            ->with('evaluasi', 'surah')
            ->whereBetween('tanggal_setoran', [$periodeAwal, $periodeAkhir])
            ->orderBy('tanggal_setoran')
            ->get();

        $totalHafalan = $this->hafalanService->hitungTotalHafalan($santriId);

        $rataNilai = $setoran
            ->filter(fn ($s) => $s->evaluasi !== null)
            ->avg(fn ($s) => (float) $s->evaluasi->nilai_akhir);

        return [
            'santri'       => $santri,
            'setoran'      => $setoran,
            'total_hafalan' => $totalHafalan,
            'rata_nilai'   => $rataNilai ? round((float) $rataNilai, 2) : 0.0,
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
        ];
    }

    /**
     * Generate laporan hafalan seluruh santri dalam satu halaqah pada periode tertentu.
     */
    public function generateLaporanHalaqah(int $halaqahId, string $periodeAwal, string $periodeAkhir): array
    {
        $halaqah = Halaqah::findOrFail($halaqahId);

        $santriList = $halaqah->santri()
            ->with(['setoran' => function ($query) use ($periodeAwal, $periodeAkhir) {
                $query->with('evaluasi', 'surah')
                    ->whereBetween('tanggal_setoran', [$periodeAwal, $periodeAkhir]);
            }])
            ->get()
            ->map(function ($santri) {
                $totalHafalan = $this->hafalanService->hitungTotalHafalan($santri->id);

                $rataNilai = $santri->setoran
                    ->filter(fn ($s) => $s->evaluasi !== null)
                    ->avg(fn ($s) => (float) $s->evaluasi->nilai_akhir);

                $santri->total_hafalan = $totalHafalan;
                $santri->rata_nilai    = $rataNilai ? round((float) $rataNilai, 2) : 0.0;

                return $santri;
            });

        return [
            'halaqah'      => $halaqah,
            'santri_list'  => $santriList,
            'periode_awal' => $periodeAwal,
            'periode_akhir' => $periodeAkhir,
        ];
    }
}
