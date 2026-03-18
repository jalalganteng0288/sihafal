<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaporanIndividuExport implements FromArray, WithHeadings, WithTitle
{
    public function __construct(private readonly array $data) {}

    public function title(): string
    {
        return 'Laporan Individu';
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Surah',
            'Jenis',
            'Ayat Awal',
            'Ayat Akhir',
            'Jumlah Ayat',
            'Kelancaran',
            'Tajwid',
            'Makhraj',
            'Nilai Akhir',
            'Kategori',
        ];
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->data['setoran'] as $setoran) {
            $jenisLabel = match ($setoran->jenis) {
                'setoran_baru' => 'Setoran Baru',
                'murajaah'     => "Muraja'ah",
                'tasmi'        => "Tasmi'",
                default        => $setoran->jenis,
            };

            $kategoriLabel = match ($setoran->evaluasi?->kategori ?? '') {
                'mumtaz'        => 'Mumtaz',
                'jayyid_jiddan' => 'Jayyid Jiddan',
                'jayyid'        => 'Jayyid',
                'maqbul'        => 'Maqbul',
                'dhaif'         => 'Dhaif',
                default         => $setoran->evaluasi?->kategori ?? '-',
            };

            $rows[] = [
                $setoran->tanggal_setoran?->format('d/m/Y') ?? '-',
                $setoran->surah?->nama_latin ?? '-',
                $jenisLabel,
                $setoran->ayat_awal ?? '-',
                $setoran->ayat_akhir ?? '-',
                $setoran->jumlah_ayat_disetor ?? 0,
                $setoran->evaluasi?->nilai_kelancaran ?? '-',
                $setoran->evaluasi?->nilai_tajwid ?? '-',
                $setoran->evaluasi?->nilai_makhraj ?? '-',
                $setoran->evaluasi?->nilai_akhir ?? '-',
                $kategoriLabel,
            ];
        }

        return $rows;
    }
}
