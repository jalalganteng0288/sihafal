@extends('layouts.app')

@section('title', 'Daftar Setoran')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pencatatan Setoran</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar setoran santri di halaqah Anda</p>
        </div>
        <a href="{{ route('ustadz.setoran.create') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Setoran
        </a>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('ustadz.setoran.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            <div class="min-w-[150px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis</label>
                <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Semua Jenis</option>
                    <option value="setoran_baru" {{ request('jenis') === 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                    <option value="murajaah" {{ request('jenis') === 'murajaah' ? 'selected' : '' }}>Murajaah</option>
                </select>
            </div>
            <div class="min-w-[180px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Surah</label>
                <select name="surah_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="">Semua Surah</option>
                    @foreach ($surahList as $surah)
                        <option value="{{ $surah->id }}" {{ request('surah_id') == $surah->id ? 'selected' : '' }}>
                            {{ $surah->nomor_surah }}. {{ $surah->nama_latin }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-150">
                    Filter
                </button>
                @if (request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis', 'surah_id']))
                    <a href="{{ route('ustadz.setoran.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition duration-150">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider w-10">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Surah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Ayat</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($setoran as $index => $s)
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $setoran->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900">
                                {{ $s->santri->nama_lengkap }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $s->surah->nomor_surah }}. {{ $s->surah->nama_latin }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-700">
                                {{ $s->ayat_awal }}–{{ $s->ayat_akhir }}
                                <span class="text-xs text-gray-400">({{ $s->jumlah_ayat_disetor }} ayat)</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($s->jenis === 'setoran_baru')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Setoran Baru
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Murajaah
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-900">
                                {{ $s->evaluasi?->nilai_akhir ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php $kategori = $s->evaluasi?->kategori @endphp
                                @if ($kategori === 'Mumtaz')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Mumtaz</span>
                                @elseif ($kategori === 'Jayyid Jiddan')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Jayyid Jiddan</span>
                                @elseif ($kategori === 'Jayyid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cyan-100 text-cyan-800">Jayyid</span>
                                @elseif ($kategori === 'Maqbul')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Maqbul</span>
                                @elseif ($kategori === 'Dhaif')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Dhaif</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-gray-600 text-xs">
                                {{ $s->tanggal_setoran->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('ustadz.setoran.edit', $s) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-150">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm font-medium">Belum ada data setoran</p>
                                @if (request()->hasAny(['tanggal_dari', 'tanggal_sampai', 'jenis', 'surah_id']))
                                    <p class="text-xs mt-1">Coba ubah filter pencarian</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($setoran->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $setoran->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
