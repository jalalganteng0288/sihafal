@extends('layouts.app')

@section('title', 'Dashboard Santri')

@section('content')
<div class="space-y-6">

    {{-- Header / Salam --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">
            Assalamu'alaikum, <span class="font-semibold text-indigo-700">{{ $santri->nama_lengkap }}</span>.
            @if ($santri->halaqah)
                Halaqah: <span class="font-medium text-gray-700">{{ $santri->halaqah->nama }}</span>.
            @endif
        </p>
    </div>

    {{-- Kartu Progress Hafalan --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        {{-- Total Juz --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Juz</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($totalHafalan['total_juz'], 1) }} <span class="text-sm font-normal text-gray-400">/ 30</span></p>
                </div>
            </div>
            {{-- Progress bar linear --}}
            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                <div class="bg-indigo-500 h-2.5 rounded-full transition-all duration-700"
                     style="width: {{ number_format($persenJuz, 2) }}%"></div>
            </div>
            <p class="text-xs text-gray-400 mt-1.5 text-right">{{ number_format($persenJuz, 1) }}% dari 30 juz</p>
        </div>

        {{-- Total Surah --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Surah</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalHafalan['total_surah'] }}</p>
                <p class="text-xs text-gray-400">dari 114 surah</p>
            </div>
        </div>

        {{-- Total Ayat --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total Ayat</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($totalHafalan['total_ayat']) }}</p>
                <p class="text-xs text-gray-400">dari 6.236 ayat</p>
            </div>
        </div>

    </div>

    {{-- Nilai Terbaru & Target Aktif --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Nilai Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Nilai Evaluasi Terbaru</h2>
            @if ($nilaiTerbaru)
                @php
                    $kategoriConfig = match($nilaiTerbaru->kategori) {
                        'mumtaz'        => ['Mumtaz', 'bg-emerald-100 text-emerald-700'],
                        'jayyid_jiddan' => ['Jayyid Jiddan', 'bg-green-100 text-green-700'],
                        'jayyid'        => ['Jayyid', 'bg-yellow-100 text-yellow-700'],
                        'maqbul'        => ['Maqbul', 'bg-orange-100 text-orange-700'],
                        'dhaif'         => ['Dhaif', 'bg-red-100 text-red-700'],
                        default         => [$nilaiTerbaru->kategori ?? '—', 'bg-gray-100 text-gray-500'],
                    };
                @endphp
                <div class="flex items-center gap-6">
                    <div class="text-center">
                        <p class="text-5xl font-bold text-indigo-600">{{ number_format($nilaiTerbaru->nilai_akhir, 1) }}</p>
                        <p class="text-xs text-gray-400 mt-1">Nilai Akhir</p>
                    </div>
                    <div class="flex-1 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Kelancaran</span>
                            <span class="font-medium text-gray-800">{{ number_format($nilaiTerbaru->nilai_kelancaran, 1) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Tajwid</span>
                            <span class="font-medium text-gray-800">{{ number_format($nilaiTerbaru->nilai_tajwid, 1) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Makhraj</span>
                            <span class="font-medium text-gray-800">{{ number_format($nilaiTerbaru->nilai_makhraj, 1) }}</span>
                        </div>
                        <div class="pt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $kategoriConfig[1] }}">
                                {{ $kategoriConfig[0] }}
                            </span>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm text-gray-400">Belum ada evaluasi tercatat</p>
                </div>
            @endif
        </div>

        {{-- Target Aktif Berikutnya --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="text-base font-semibold text-gray-800 mb-4">Target Aktif</h2>
            @forelse ($targetAktif as $target)
                @php
                    $sisaHari = \Carbon\Carbon::today()->diffInDays($target->batas_waktu, false);
                    $persen   = min((float) $target->persentase_pencapaian, 100);
                @endphp
                <div class="mb-4 last:mb-0 p-3 bg-indigo-50 rounded-lg border border-indigo-100">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-sm font-semibold text-indigo-800">Target {{ $target->target_juz }} Juz</p>
                            <p class="text-xs text-indigo-500">
                                Batas: {{ $target->batas_waktu->format('d M Y') }}
                                @if ($sisaHari >= 0)
                                    <span class="ml-1 text-gray-500">({{ $sisaHari }} hari lagi)</span>
                                @endif
                            </p>
                        </div>
                        <span class="text-sm font-bold text-indigo-700">{{ number_format($persen, 1) }}%</span>
                    </div>
                    <div class="w-full bg-indigo-100 rounded-full h-2 overflow-hidden">
                        <div class="bg-indigo-500 h-2 rounded-full transition-all duration-700"
                             style="width: {{ $persen }}%"></div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <p class="text-sm text-gray-400">Tidak ada target aktif saat ini</p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- Grafik Tren Nilai Evaluasi --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="text-base font-semibold text-gray-800 mb-4">Tren Nilai Evaluasi</h2>
        @if (count($chartData) > 0)
            <div class="relative" style="height: 220px;">
                <canvas id="chartNilai"></canvas>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-10 text-center">
                <svg class="w-10 h-10 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <p class="text-sm text-gray-400">Belum ada data evaluasi untuk ditampilkan</p>
            </div>
        @endif
    </div>

    {{-- Riwayat Setoran Terbaru --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-800">Riwayat Setoran Terbaru</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Surah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ayat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($setoranTerbaru as $setoran)
                        @php
                            $jenisLabel = match($setoran->jenis) {
                                'setoran_baru' => ['Setoran Baru', 'bg-indigo-100 text-indigo-700'],
                                'murajaah'     => ["Muraja'ah", 'bg-blue-100 text-blue-700'],
                                'tasmi'        => ["Tasmi'", 'bg-violet-100 text-violet-700'],
                                default        => [$setoran->jenis, 'bg-gray-100 text-gray-600'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap text-xs">
                                {{ $setoran->tanggal_setoran?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
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
                            <td class="px-4 py-3 text-center text-gray-700 whitespace-nowrap">
                                {{ $setoran->jumlah_ayat_disetor ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if ($setoran->evaluasi)
                                    <span class="font-semibold text-indigo-700">{{ number_format($setoran->evaluasi->nilai_akhir, 1) }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">Belum dievaluasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-center">
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

@if (count($chartData) > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('chartNilai').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Nilai Akhir',
                data: {!! json_encode($chartData) !!},
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#6366f1',
                pointRadius: 4,
                pointHoverRadius: 6,
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
                        label: ctx => 'Nilai: ' + ctx.parsed.y.toFixed(1)
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 100,
                    ticks: { stepSize: 20 },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush
@endif
