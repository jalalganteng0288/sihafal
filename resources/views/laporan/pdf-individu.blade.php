<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hafalan Individu</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #1a1a1a; }

        .header { text-align: center; border-bottom: 2px solid #059669; padding-bottom: 12px; margin-bottom: 16px; }
        .header .pesantren { font-size: 16px; font-weight: bold; color: #065f46; }
        .header .judul { font-size: 13px; font-weight: bold; margin-top: 4px; }
        .header .periode { font-size: 10px; color: #6b7280; margin-top: 2px; }

        .info-santri { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px 14px; margin-bottom: 16px; }
        .info-santri table { width: 100%; }
        .info-santri td { padding: 2px 6px; font-size: 11px; }
        .info-santri td:first-child { font-weight: bold; width: 130px; color: #374151; }
        .info-santri td:nth-child(2) { width: 10px; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.data th {
            background: #059669;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data tr:nth-child(even) td { background: #f9fafb; }
        table.data .center { text-align: center; }
        table.data .right { text-align: right; }

        .badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-mumtaz        { background: #d1fae5; color: #065f46; }
        .badge-jayyid_jiddan { background: #dcfce7; color: #166534; }
        .badge-jayyid        { background: #fef9c3; color: #854d0e; }
        .badge-maqbul        { background: #ffedd5; color: #9a3412; }
        .badge-dhaif         { background: #fee2e2; color: #991b1b; }
        .badge-default       { background: #f3f4f6; color: #6b7280; }

        .ringkasan { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 6px; padding: 10px 14px; }
        .ringkasan h3 { font-size: 11px; font-weight: bold; color: #065f46; margin-bottom: 8px; }
        .ringkasan table { width: 100%; }
        .ringkasan td { padding: 2px 6px; font-size: 11px; }
        .ringkasan td:first-child { font-weight: bold; width: 160px; color: #374151; }
        .ringkasan td:nth-child(2) { width: 10px; }

        .empty-state { text-align: center; padding: 30px; color: #9ca3af; font-style: italic; }

        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 8px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <div class="pesantren">Pondok Pesantren Attaupiqillah</div>
        <div class="judul">Laporan Hafalan Individu</div>
        <div class="periode">
            Periode: {{ \Carbon\Carbon::parse($periode_awal)->format('d M Y') }}
            s/d {{ \Carbon\Carbon::parse($periode_akhir)->format('d M Y') }}
        </div>
    </div>

    {{-- Info Santri --}}
    <div class="info-santri">
        <table>
            <tr>
                <td>Nama Santri</td>
                <td>:</td>
                <td>{{ $santri->nama_lengkap }}</td>
                <td style="width:40px"></td>
                <td style="font-weight:bold; width:130px; color:#374151;">Nomor Induk</td>
                <td style="width:10px">:</td>
                <td>{{ $santri->nomor_induk }}</td>
            </tr>
            <tr>
                <td>Halaqah</td>
                <td>:</td>
                <td>{{ $santri->halaqah?->nama ?? '-' }}</td>
                <td></td>
                <td style="font-weight:bold; color:#374151;">Ustadz</td>
                <td>:</td>
                <td>{{ $santri->halaqah?->ustadz?->nama_lengkap ?? '-' }}</td>
            </tr>
        </table>
    </div>

    {{-- Tabel Setoran --}}
    @if ($setoran->isEmpty())
        <div class="empty-state">Tidak ada data setoran pada periode yang dipilih.</div>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Surah</th>
                    <th>Jenis</th>
                    <th class="center">Ayat</th>
                    <th class="center">Jml Ayat</th>
                    <th class="center">Kelancaran</th>
                    <th class="center">Tajwid</th>
                    <th class="center">Makhraj</th>
                    <th class="center">Nilai Akhir</th>
                    <th class="center">Kategori</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($setoran as $s)
                    @php
                        $jenisLabel = match($s->jenis) {
                            'setoran_baru' => 'Setoran Baru',
                            'murajaah'     => "Muraja'ah",
                            'tasmi'        => "Tasmi'",
                            default        => $s->jenis,
                        };
                        $kategori      = $s->evaluasi?->kategori ?? '';
                        $badgeClass    = match($kategori) {
                            'mumtaz'        => 'badge-mumtaz',
                            'jayyid_jiddan' => 'badge-jayyid_jiddan',
                            'jayyid'        => 'badge-jayyid',
                            'maqbul'        => 'badge-maqbul',
                            'dhaif'         => 'badge-dhaif',
                            default         => 'badge-default',
                        };
                        $kategoriLabel = match($kategori) {
                            'mumtaz'        => 'Mumtaz',
                            'jayyid_jiddan' => 'Jayyid Jiddan',
                            'jayyid'        => 'Jayyid',
                            'maqbul'        => 'Maqbul',
                            'dhaif'         => 'Dhaif',
                            default         => $kategori ?: '-',
                        };
                    @endphp
                    <tr>
                        <td>{{ $s->tanggal_setoran?->format('d/m/Y') ?? '-' }}</td>
                        <td>{{ $s->surah?->nama_latin ?? '-' }}</td>
                        <td>{{ $jenisLabel }}</td>
                        <td class="center">{{ $s->ayat_awal ?? '-' }}–{{ $s->ayat_akhir ?? '-' }}</td>
                        <td class="center">{{ $s->jumlah_ayat_disetor ?? 0 }}</td>
                        <td class="center">{{ $s->evaluasi?->nilai_kelancaran ?? '-' }}</td>
                        <td class="center">{{ $s->evaluasi?->nilai_tajwid ?? '-' }}</td>
                        <td class="center">{{ $s->evaluasi?->nilai_makhraj ?? '-' }}</td>
                        <td class="center">{{ $s->evaluasi ? number_format($s->evaluasi->nilai_akhir, 1) : '-' }}</td>
                        <td class="center">
                            <span class="badge {{ $badgeClass }}">{{ $kategoriLabel }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Ringkasan --}}
        <div class="ringkasan">
            <h3>Ringkasan Hafalan</h3>
            <table>
                <tr>
                    <td>Total Ayat Disetorkan</td>
                    <td>:</td>
                    <td>{{ $total_hafalan['total_ayat'] }} ayat</td>
                    <td style="width:40px"></td>
                    <td style="font-weight:bold; width:160px; color:#374151;">Total Surah</td>
                    <td style="width:10px">:</td>
                    <td>{{ $total_hafalan['total_surah'] }} surah</td>
                </tr>
                <tr>
                    <td>Total Juz</td>
                    <td>:</td>
                    <td>{{ number_format($total_hafalan['total_juz'], 2) }} juz</td>
                    <td></td>
                    <td style="font-weight:bold; color:#374151;">Rata-rata Nilai</td>
                    <td>:</td>
                    <td>{{ $rata_nilai > 0 ? number_format($rata_nilai, 2) : '-' }}</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="footer">
        Dicetak pada: {{ now()->format('d M Y, H:i') }} WIB
    </div>

</body>
</html>
