@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}. Berikut ringkasan data hari ini.</p>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Total Santri Aktif --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Santri Aktif</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($totalSantriAktif) }}</p>
            </div>
        </div>

        {{-- Total Ustadz --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Ustadz</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($totalUstadz) }}</p>
            </div>
        </div>

        {{-- Total Halaqah --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Halaqah</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($totalHalaqah) }}</p>
            </div>
        </div>

        {{-- Setoran Bulan Ini --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="flex-shrink-0 w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Setoran Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-900 mt-0.5">{{ number_format($totalSetoran) }}</p>
            </div>
        </div>

    </div>

    {{-- Grafik Batang --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Setoran Hafalan per Bulan</h2>
        <div class="relative" style="height: 280px;">
            <canvas id="grafikBulanan"></canvas>
        </div>
    </div>

    {{-- Tabel Bawah --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Setoran Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Setoran Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Surah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ayat</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($setoranTerbaru as $setoran)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $setoran->santri?->nama_lengkap ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $setoran->surah?->nama_latin ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-600 whitespace-nowrap">
                                    {{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }}
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @php
                                        $jenisLabel = match($setoran->jenis) {
                                            'setoran_baru'  => ['Baru', 'bg-green-100 text-green-700'],
                                            'murajaah'      => ['Muraja\'ah', 'bg-blue-100 text-blue-700'],
                                            'tasmi'         => ['Tasmi\'', 'bg-purple-100 text-purple-700'],
                                            default         => [$setoran->jenis, 'bg-gray-100 text-gray-600'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $jenisLabel[1] }}">
                                        {{ $jenisLabel[0] }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    @if ($setoran->evaluasi)
                                        <span class="font-semibold text-gray-800">{{ $setoran->evaluasi->nilai ?? '—' }}</span>
                                    @else
                                        <span class="text-gray-400">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-gray-500 whitespace-nowrap text-xs">
                                    {{ $setoran->tanggal_setoran?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    Belum ada data setoran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Santri Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-semibold text-gray-800">Santri Terbaru Terdaftar</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Induk</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Halaqah</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($santriTerbaru as $santri)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $santri->nama_lengkap }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-xs whitespace-nowrap">
                                    {{ $santri->nomor_induk }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                    {{ $santri->halaqah?->nama ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-right text-gray-500 text-xs whitespace-nowrap">
                                    {{ $santri->tanggal_masuk?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-400 text-sm">
                                    Belum ada data santri
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    (function () {
        const labels = @json($dataGrafikBulanan['labels']);
        const data   = @json($dataGrafikBulanan['data']);

        const ctx = document.getElementById('grafikBulanan').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Setoran',
                    data: data,
                    backgroundColor: 'rgba(22, 163, 74, 0.75)',
                    borderColor: 'rgba(22, 163, 74, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} setoran`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { size: 11 }
                        },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    }
                }
            }
        });
    })();
</script>
@endpush
