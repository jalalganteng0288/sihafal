@extends('layouts.app')

@section('title', 'Detail Evaluasi')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('ustadz.setoran.index') }}"
           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Evaluasi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Hasil penilaian setoran hafalan</p>
        </div>
    </div>

    {{-- Info Setoran --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Informasi Setoran</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Nama Santri</p>
                <p class="text-sm font-semibold text-gray-900">{{ $setoran->santri->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Tanggal Setoran</p>
                <p class="text-sm font-semibold text-gray-900">{{ $setoran->tanggal_setoran->translatedFormat('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Surah</p>
                <p class="text-sm font-semibold text-gray-900">
                    {{ $setoran->surah->nomor_surah }}. {{ $setoran->surah->nama_latin }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Rentang Ayat</p>
                <p class="text-sm font-semibold text-gray-900">
                    Ayat {{ $setoran->ayat_awal }} – {{ $setoran->ayat_akhir }}
                    <span class="text-gray-400 font-normal">({{ $setoran->jumlah_ayat_disetor }} ayat)</span>
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Jenis</p>
                @if ($setoran->jenis === 'setoran_baru')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Setoran Baru
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                        Murajaah
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Nilai Per Aspek --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Nilai Per Aspek</h2>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Kelancaran --}}
            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                <p class="text-xs font-medium text-emerald-600 mb-1">Kelancaran</p>
                <p class="text-3xl font-bold text-emerald-700">{{ number_format($setoran->evaluasi->nilai_kelancaran, 1) }}</p>
                <div class="mt-2 w-full bg-emerald-200 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $setoran->evaluasi->nilai_kelancaran }}%"></div>
                </div>
            </div>
            {{-- Tajwid --}}
            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                <p class="text-xs font-medium text-emerald-600 mb-1">Tajwid</p>
                <p class="text-3xl font-bold text-emerald-700">{{ number_format($setoran->evaluasi->nilai_tajwid, 1) }}</p>
                <div class="mt-2 w-full bg-emerald-200 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $setoran->evaluasi->nilai_tajwid }}%"></div>
                </div>
            </div>
            {{-- Makhraj --}}
            <div class="bg-emerald-50 rounded-lg p-4 text-center">
                <p class="text-xs font-medium text-emerald-600 mb-1">Makhraj</p>
                <p class="text-3xl font-bold text-emerald-700">{{ number_format($setoran->evaluasi->nilai_makhraj, 1) }}</p>
                <div class="mt-2 w-full bg-emerald-200 rounded-full h-1.5">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $setoran->evaluasi->nilai_makhraj }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Nilai Akhir & Kategori --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Hasil Evaluasi</h2>
        <div class="flex flex-col sm:flex-row items-center gap-6">
            <div class="text-center">
                <p class="text-xs text-gray-400 mb-1">Nilai Akhir</p>
                <p class="text-5xl font-bold text-gray-900">{{ number_format($setoran->evaluasi->nilai_akhir, 2) }}</p>
                <p class="text-xs text-gray-400 mt-1">dari 100</p>
            </div>
            <div class="flex-1 flex flex-col items-center sm:items-start gap-2">
                <p class="text-xs text-gray-400">Kategori</p>
                @php $kategori = $setoran->evaluasi->kategori; @endphp
                @if ($kategori === 'Mumtaz')
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        ⭐ Mumtaz
                    </span>
                @elseif ($kategori === 'Jayyid Jiddan')
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        Jayyid Jiddan
                    </span>
                @elseif ($kategori === 'Jayyid')
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                        Jayyid
                    </span>
                @elseif ($kategori === 'Maqbul')
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-orange-100 text-orange-800">
                        Maqbul
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                        Dhaif
                    </span>
                @endif
                <div class="text-xs text-gray-500 mt-1 space-y-0.5">
                    <p>≥ 90 = Mumtaz &nbsp;|&nbsp; ≥ 80 = Jayyid Jiddan &nbsp;|&nbsp; ≥ 70 = Jayyid</p>
                    <p>≥ 60 = Maqbul &nbsp;|&nbsp; &lt; 60 = Dhaif</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Catatan --}}
    @if ($setoran->evaluasi->catatan_evaluasi)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Catatan Evaluasi</h2>
        <p class="text-sm text-gray-700 leading-relaxed">{{ $setoran->evaluasi->catatan_evaluasi }}</p>
    </div>
    @endif

    {{-- Aksi --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('ustadz.setoran.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Setoran
        </a>
        <a href="{{ route('ustadz.evaluasi.riwayat', $setoran->santri) }}"
           class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition duration-150">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            Riwayat Evaluasi Santri
        </a>
    </div>

</div>
@endsection
