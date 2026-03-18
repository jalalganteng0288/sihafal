<?php

namespace App\Services;

use App\Models\Santri;
use App\Models\Surah;
use App\Models\Target;

class HafalanService
{
    /**
     * Hitung total hafalan santri berdasarkan setoran bertipe 'setoran_baru'.
     */
    public function hitungTotalHafalan(int $santriId): array
    {
        $santri = Santri::findOrFail($santriId);

        $setoranBaru = $santri->setoran()->where('jenis', 'setoran_baru');

        $totalAyat  = (int) $setoranBaru->sum('jumlah_ayat_disetor');
        $totalSurah = (int) $setoranBaru->distinct('surah_id')->count('surah_id');
        $totalJuz   = round($totalAyat / 604 * 30, 4);

        return [
            'total_ayat'  => $totalAyat,
            'total_surah' => $totalSurah,
            'total_juz'   => $totalJuz,
        ];
    }

    /**
     * Validasi rentang ayat untuk surah tertentu.
     */
    public function validasiRentangAyat(int $surahId, int $ayatAwal, int $ayatAkhir): bool
    {
        $surah = Surah::find($surahId);

        if (! $surah) {
            return false;
        }

        if ($ayatAwal < 1) {
            return false;
        }

        if ($ayatAkhir < $ayatAwal) {
            return false;
        }

        if ($ayatAkhir > $surah->jumlah_ayat) {
            return false;
        }

        return true;
    }

    /**
     * Hitung persentase pencapaian target hafalan santri.
     */
    public function hitungPersentaseTarget(int $santriId, int $targetId): float
    {
        $target = Target::findOrFail($targetId);

        $hafalan  = $this->hitungTotalHafalan($santriId);
        $totalJuz = $hafalan['total_juz'];

        if ($target->target_juz <= 0) {
            return 0.0;
        }

        $persentase = ($totalJuz / $target->target_juz) * 100;

        return min((float) round($persentase, 2), 100.0);
    }
}
