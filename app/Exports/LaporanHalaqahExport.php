<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanHalaqahExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'Rekap Halaqah';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Santri',
            'Total Ayat',
            'Total Surah',
            'Total Juz',
            'Rata-rata Nilai',
        ];
    }

    public function array(): array
    {
        $rows = [];
        $no   = 1;

        foreach ($this->data['santri_list'] as $santri) {
            $totalHafalan = $santri->total_hafalan;

            $rows[] = [
                $no++,
                $santri->nama_lengkap,
                $totalHafalan['total_ayat'] ?? 0,
                $totalHafalan['total_surah'] ?? 0,
                number_format($totalHafalan['total_juz'] ?? 0, 2),
                $santri->rata_nilai ?? 0,
            ];
        }

        return $rows;
    }
}
