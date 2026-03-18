@extends('layouts.app')

@section('title', 'Riwayat Evaluasi - ' . $santri->nama_lengkap)

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('ustadz.setoran.index') }}"
           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Evaluasi</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $santri->nama_lengkap }}</p>
        </div>
    </div>

    @if ($setoranList->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <p class="text-sm font-medium text-gray-500">Belum ada riwayat evaluasi untuk santri ini</p>
        </div>
    @else

        {{-- Grafik Tren Nilai --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tren Nilai Akhir</h2>
            <div class="relative" style="height: 280px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        {{-- Statistik Ringkas --}}
        @php
            $nilaiList = $setoranList->map(fn($s) => (float) $s->evaluasi->nilai_akhir);
            $rataRata  = $nilaiList->avg();
            $tertinggi = $nilaiList->max();
            $terendah  = $nilaiList->min();
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                <p class="text-xs text-gray-400 mb-1">Rata-rata Nilai</p>
                <p class="text-3xl font-bold text-emerald-600">{{ number_format($rataRata, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                <p class="text-xs text-gray-400 mb-1">Nilai Tertinggi</p>
                <p class="text-3xl font-bold text-blue-600">{{ number_format($tertinggi, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                <p class="text-xs text-gray-400 mb-1">Nilai Terendah</p>
                <p class="text-3xl font-bold text-orange-500">{{ number_format($terendah, 2) }}</p>
            </div>
        </div>

        {{-- Tabel Riwayat --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-sm font-semibold text-gray-700">Daftar Evaluasi ({{ $setoranList->count() }} setoran)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Surah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kelancaran</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tajwid</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Makhraj</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($setoranList as $s)
                            <tr class="hover:bg-gray-50 transition duration-100">
                                <td class="px-4 py-3 text-gray-600 text-xs whitespace-nowrap">
                                    {{ $s->tanggal_setoran->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-gray-800 font-medium">
                                    {{ $s->surah->nomor_surah }}. {{ $s->surah->nama_latin }}
                                    <span class="block text-xs text-gray-400 font-normal">
                                        Ayat {{ $s->ayat_awal }}–{{ $s->ayat_akhir }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ number_format($s->evaluasi->nilai_kelancaran, 1) }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ number_format($s->evaluasi->nilai_tajwid, 1) }}
                                </td>
                                <td class="px-4 py-3 text-center text-gray-700">
                                    {{ number_format($s->evaluasi->nilai_makhraj, 1) }}
                                </td>
                                <td class="px-4 py-3 text-center font-bold text-gray-900">
                                    {{ number_format($s->evaluasi->nilai_akhir, 2) }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @php $kategori = $s->evaluasi->kategori; @endphp
                                    @if ($kategori === 'Mumtaz')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Mumtaz</span>
                                    @elseif ($kategori === 'Jayyid Jiddan')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Jayyid Jiddan</span>
                                    @elseif ($kategori === 'Jayyid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Jayyid</span>
                                    @elseif ($kategori === 'Maqbul')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Maqbul</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dhaif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('ustadz.evaluasi.show', $s) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endif

    {{-- Tombol Kembali --}}
    <div>
        <a href="{{ route('ustadz.setoran.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Setoran
        </a>
    </div>

</div>
@endsection

@if (!$setoranList->isEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const labels = @json($chartLabels);
    const data   = @json($chartData);

    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Nilai Akhir',
                data: data,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2.5,
                pointBackgroundColor: '#10b981',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' Nilai Akhir: ' + ctx.parsed.y.toFixed(2)
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: {
                        stepSize: 20,
                        font: { size: 11 }
                    },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    ticks: {
                        font: { size: 10 },
                        maxRotation: 30,
                    },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
@endif
