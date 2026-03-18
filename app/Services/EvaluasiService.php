<?php

namespace App\Services;

class EvaluasiService
{
    /**
     * Hitung nilai akhir evaluasi dari rata-rata tiga komponen.
     */
    public function hitungNilaiAkhir(float $kelancaran, float $tajwid, float $makhraj): float
    {
        return round(($kelancaran + $tajwid + $makhraj) / 3, 2);
    }

    /**
     * Klasifikasikan nilai akhir ke dalam kategori hafalan.
     */
    public function klasifikasiNilai(float $nilaiAkhir): string
    {
        if ($nilaiAkhir >= 90) {
            return 'Mumtaz';
        }

        if ($nilaiAkhir >= 80) {
            return 'Jayyid Jiddan';
        }

        if ($nilaiAkhir >= 70) {
            return 'Jayyid';
        }

        if ($nilaiAkhir >= 60) {
            return 'Maqbul';
        }

        return 'Dhaif';
    }
}
