@extends('layouts.app')

@section('title', 'Manajemen Santri')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Santri</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data seluruh santri pesantren</p>
        </div>
        <a href="{{ route('admin.santri.create') }}"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition duration-150 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Santri
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

    {{-- Filter & Search --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
        <form method="GET" action="{{ route('admin.santri.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Cari Santri</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Nama atau nomor induk..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                >
            </div>
            <div class="min-w-[160px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Urutkan</label>
                <select name="sort" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="nama_lengkap" {{ $sort === 'nama_lengkap' ? 'selected' : '' }}>Nama</option>
                    <option value="halaqah" {{ $sort === 'halaqah' ? 'selected' : '' }}>Halaqah</option>
                    <option value="hafalan" {{ $sort === 'hafalan' ? 'selected' : '' }}>Total Hafalan</option>
                </select>
            </div>
            <div class="min-w-[120px]">
                <label class="block text-xs font-medium text-gray-600 mb-1">Arah</label>
                <select name="direction" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="asc" {{ $direction === 'asc' ? 'selected' : '' }}>A–Z / Terkecil</option>
                    <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>Z–A / Terbesar</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-150">
                    Cari
                </button>
                @if ($search || $sort !== 'nama_lengkap' || $direction !== 'asc')
                    <a href="{{ route('admin.santri.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition duration-150">
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
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nomor Induk</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Halaqah</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Hafalan (Ayat)</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($santri as $index => $s)
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-4 py-3 text-gray-500">
                                {{ $santri->firstItem() + $index }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900">{{ $s->nama_lengkap }}</div>
                                <div class="text-xs text-gray-400">{{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                            </td>
                            <td class="px-4 py-3 text-gray-700 font-mono text-xs">{{ $s->nomor_induk }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ $s->halaqah?->nama ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-right text-gray-700 font-medium">
                                {{ number_format($s->total_hafalan_ayat) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if ($s->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.santri.edit', $s) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>

                                    @if ($s->is_active)
                                        <div x-data="{ open: false }">
                                            <button @click="open = true"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition duration-150">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Nonaktifkan
                                            </button>

                                            {{-- Modal Konfirmasi --}}
                                            <div x-show="open" x-cloak
                                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                                                 @keydown.escape.window="open = false">
                                                <div class="bg-white rounded-xl shadow-xl p-6 max-w-sm w-full mx-4" @click.stop>
                                                    <div class="flex items-center gap-3 mb-4">
                                                        <div class="flex-shrink-0 w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h3 class="font-semibold text-gray-900">Nonaktifkan Santri</h3>
                                                            <p class="text-sm text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
                                                        </div>
                                                    </div>
                                                    <p class="text-sm text-gray-700 mb-5">
                                                        Apakah Anda yakin ingin menonaktifkan <strong>{{ $s->nama_lengkap }}</strong>?
                                                        Riwayat hafalan akan tetap tersimpan.
                                                    </p>
                                                    <div class="flex gap-3 justify-end">
                                                        <button @click="open = false"
                                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition duration-150">
                                                            Batal
                                                        </button>
                                                        <form method="POST" action="{{ route('admin.santri.deactivate', $s) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition duration-150">
                                                                Ya, Nonaktifkan
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-sm font-medium">Tidak ada data santri</p>
                                @if ($search)
                                    <p class="text-xs mt-1">Coba ubah kata kunci pencarian</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($santri->hasPages())
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $santri->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
