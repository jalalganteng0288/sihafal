@extends('layouts.app')

@section('title', 'Dashboard Ustadz')

@section('content')
<div class="space-y-6">

    {{-- Header / Salam --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">
            Assalamu'alaikum, <span class="font-semibold text-emerald-700">{{ $ustadz->nama_lengkap }}</span>.
            Berikut ringkasan halaqah Anda hari ini.
        </p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Santri --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Santri</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ $totalSantri }}</p>
            </div>
        </div>

        {{-- Rata-rata Nilai Bulan Ini --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Rata-rata Nilai Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">
                    {{ $rataRataNilai !== null ? $rataRataNilai : '—' }}
                </p>
            </div>
        </div>

        {{-- Target Aktif --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Target Aktif</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ $targetAktif }}</p>
            </div>
        </div>

        {{-- Target Terlewat --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 {{ $targetTerlewat > 0 ? 'bg-red-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 {{ $targetTerlewat > 0 ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Target Terlewat</p>
                <p class="text-3xl font-bold {{ $targetTerlewat > 0 ? 'text-red-600' : 'text-gray-900' }} mt-0.5">{{ $targetTerlewat }}</p>
            </div>
        </div>

    </div>

    {{-- Peringatan: Santri Tidak Menyetor --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
            @if ($santriTidakSetor->count() > 0)
                <span class="flex-shrink-0 w-2.5 h-2.5 rounded-full bg-red-500 animate-pulse"></span>
            @endif
            <h2 class="text-base font-semibold text-gray-800">
                Peringatan: Santri Tidak Menyetor
                <span class="text-xs font-normal text-gray-500">(7 hari terakhir)</span>
            </h2>
            @if ($santriTidakSetor->count() > 0)
                <span class="ml-auto inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                    {{ $santriTidakSetor->count() }} santri
                </span>
            @endif
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Halaqah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Terakhir Menyetor</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($santriTidakSetor as $santri)
                        @php
                            $hariSejak = $santri->terakhir_setor
                                ? \Carbon\Carbon::parse($santri->terakhir_setor)->diffInDays(now())
                                : null;
                        @endphp
                        <tr class="hover:bg-red-50 transition duration-100">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                {{ $santri->nama_lengkap }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                {{ $santri->halaqah?->nama ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($santri->terakhir_setor)
                                    <span class="text-gray-700 text-xs">
                                        {{ \Carbon\Carbon::parse($santri->terakhir_setor)->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs italic">Belum pernah setor</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($hariSejak === null)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Belum ada setoran
                                    </span>
                                @elseif ($hariSejak >= 14)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        {{ $hariSejak }} hari lalu
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                        {{ $hariSejak }} hari lalu
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center">
                                <svg class="w-10 h-10 mx-auto mb-2 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Semua santri sudah menyetor dalam 7 hari terakhir</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Setoran Terbaru --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Setoran Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Surah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($setoranTerbaru as $setoran)
                        @php
                            $jenisLabel = match($setoran->jenis) {
                                'setoran_baru' => ['Setoran Baru', 'bg-emerald-100 text-emerald-700'],
                                'murajaah'     => ["Muraja'ah", 'bg-blue-100 text-blue-700'],
                                'tasmi'        => ["Tasmi'", 'bg-purple-100 text-purple-700'],
                                default        => [$setoran->jenis, 'bg-gray-100 text-gray-600'],
                            };
                            $kategoriLabel = match($setoran->evaluasi?->kategori ?? '') {
                                'mumtaz'    => ['Mumtaz', 'bg-emerald-100 text-emerald-700'],
                                'jayyid_jiddan' => ['Jayyid Jiddan', 'bg-green-100 text-green-700'],
                                'jayyid'    => ['Jayyid', 'bg-yellow-100 text-yellow-700'],
                                'maqbul'    => ['Maqbul', 'bg-orange-100 text-orange-700'],
                                'dhaif'     => ['Dhaif', 'bg-red-100 text-red-700'],
                                default     => [$setoran->evaluasi?->kategori ?? '—', 'bg-gray-100 text-gray-500'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                {{ $setoran->santri?->nama_lengkap ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                {{ $setoran->surah?->nama_latin ?? '—' }}
                                @if ($setoran->ayat_awal && $setoran->ayat_akhir)
                                    <span class="text-xs text-gray-400">({{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }})</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $jenisLabel[1] }}">
                                    {{ $jenisLabel[0] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($setoran->evaluasi)
                                    <span class="font-semibold text-gray-800">{{ number_format($setoran->evaluasi->nilai_akhir, 1) }}</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($setoran->evaluasi)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $kategoriLabel[1] }}">
                                        {{ $kategoriLabel[0] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500 text-xs whitespace-nowrap">
                                {{ $setoran->tanggal_setoran?->format('d M Y') ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <p class="text-sm font-medium text-gray-500">Belum ada setoran tercatat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
