@extends('layouts.app')

@section('title', 'Evaluasi Hafalan')

@section('content')
<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">Evaluasi Hafalan</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar evaluasi setoran santri di halaqah Anda.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Santri</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Surah</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai Akhir</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($setoranList as $setoran)
                        @php
                            $kategoriConfig = match($setoran->evaluasi->kategori ?? '') {
                                'Mumtaz'        => 'bg-emerald-100 text-emerald-700',
                                'Jayyid Jiddan' => 'bg-green-100 text-green-700',
                                'Jayyid'        => 'bg-yellow-100 text-yellow-700',
                                'Maqbul'        => 'bg-orange-100 text-orange-700',
                                'Dhaif'         => 'bg-red-100 text-red-700',
                                default         => 'bg-gray-100 text-gray-600',
                            };
                            $jenisLabel = match($setoran->jenis) {
                                'setoran_baru' => ['Setoran Baru', 'bg-indigo-100 text-indigo-700'],
                                'murajaah'     => ["Muraja'ah", 'bg-blue-100 text-blue-700'],
                                default        => [$setoran->jenis, 'bg-gray-100 text-gray-600'],
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                                {{ $setoran->santri?->nama_lengkap ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                {{ $setoran->surah?->nama_latin ?? '—' }}
                                <span class="text-xs text-gray-400">({{ $setoran->ayat_awal }}–{{ $setoran->ayat_akhir }})</span>
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $jenisLabel[1] }}">
                                    {{ $jenisLabel[0] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-gray-800 whitespace-nowrap">
                                {{ number_format($setoran->evaluasi->nilai_akhir, 1) }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $kategoriConfig }}">
                                    {{ $setoran->evaluasi->kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-500 text-xs whitespace-nowrap">
                                {{ $setoran->tanggal_setoran?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                <a href="{{ route('ustadz.evaluasi.show', $setoran) }}"
                                   class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-center text-gray-400 text-sm">
                                Belum ada data evaluasi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($setoranList->hasPages())
            <div class="px-4 py-3 border-t border-gray-100">
                {{ $setoranList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
