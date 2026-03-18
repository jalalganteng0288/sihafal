<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekap Halaqah</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1a1a1a; }

        .header { text-align: center; border-bottom: 2px solid #059669; padding-bottom: 12px; margin-bottom: 16px; }
        .header .pesantren { font-size: 16px; font-weight: bold; color: #065f46; }
        .header .judul { font-size: 13px; font-weight: bold; margin-top: 4px; }
        .header .sub { font-size: 11px; color: #374151; margin-top: 3px; }
        .header .periode { font-size: 10px; color: #6b7280; margin-top: 2px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.data th {
            background: #059669;
            color: white;
            padding: 7px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        table.data td { padding: 6px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        table.data tr:nth-child(even) td { background: #f9fafb; }
        table.data .center { text-align: center; }
        table.data .right { text-align: right; }

        .empty-state { text-align: center; padding: 30px; color: #9ca3af; font-style: italic; }

        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="pesantren">Pondok Pesantren Attaupiqillah</div>
        <div class="judul">Laporan Rekap Halaqah</div>
        <div class="sub">{{ $halaqah->nama }}
            @if ($halaqah->ustadz)
                — Ustadz {{ $halaqah->ustadz->nama_lengkap }}
            @endif
        </div>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($periode_awal)->format('d M Y') }}
            s/d {{ \Carbon\Carbon::parse($periode_akhir)->format('d M Y') }}
        </div>
    </div>

    {{-- Tabel Rekap --}}
    @if ($santri_list->isEmpty())
        <div class="empty-state">Tidak ada data santri pada halaqah ini.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th style="width:40px" class="center">No</th>
                    <th>Nama Santri</th>
                    <th class="center">Total Ayat</th>
                    <th class="center">Total Surah</th>
                    <th class="center">Total Juz</th>
                    <th class="center">Rata-rata Nilai</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($santri_list as $index => $santri)
                    @php
                        $totalHafalan = $santri->total_hafalan;
                    @endphp
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $santri->nama_lengkap }}</td>
                        <td class="center">{{ $totalHafalan['total_ayat'] ?? 0 }}</td>
                        <td class="center">{{ $totalHafalan['total_surah'] ?? 0 }}</td>
                        <td class="center">{{ number_format($totalHafalan['total_juz'] ?? 0, 2) }}</td>
                        <td class="center">
                            {{ $santri->rata_nilai > 0 ? number_format($santri->rata_nilai, 2) : '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB
    </div>

</body>
</html>
