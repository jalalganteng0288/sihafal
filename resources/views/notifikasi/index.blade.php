@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notifikasi</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar notifikasi untuk akun Anda.</p>
        </div>

        @if ($notifikasi->where('is_dibaca', false)->count() > 0)
            <form method="POST" action="{{ route('notifikasi.read-all') }}">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    {{-- Flash message --}}
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- Daftar Notifikasi --}}
    <div class="space-y-3">
        @forelse ($notifikasi as $item)
            @php
                $belumDibaca = ! $item->is_dibaca;

                $tipeConfig = match($item->tipe) {
                    'tidak_setor'    => ['label' => 'Tidak Setor',    'badge' => 'bg-orange-100 text-orange-700', 'icon_color' => 'text-orange-500'],
                    'target_terlewat'=> ['label' => 'Target Terlewat','badge' => 'bg-red-100 text-red-700',    'icon_color' => 'text-red-500'],
                    default          => ['label' => 'Sistem',         'badge' => 'bg-blue-100 text-blue-700',  'icon_color' => 'text-blue-500'],
                };
            @endphp

            <div class="flex items-start gap-4 p-4 rounded-xl border transition
                {{ $belumDibaca ? 'bg-indigo-50 border-indigo-200' : 'bg-white border-gray-200' }}">

                {{-- Ikon --}}
                <div class="flex-shrink-0 mt-0.5">
                    @if ($item->tipe === 'tidak_setor')
                        <svg class="w-6 h-6 {{ $tipeConfig['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @elseif ($item->tipe === 'target_terlewat')
                        <svg class="w-6 h-6 {{ $tipeConfig['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6 {{ $tipeConfig['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    @endif
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-2 flex-wrap">
                            {{-- Dot indicator belum dibaca --}}
                            @if ($belumDibaca)
                                <span class="inline-block w-2 h-2 rounded-full bg-indigo-500 flex-shrink-0"></span>
                            @endif
                            <p class="text-sm font-semibold {{ $belumDibaca ? 'text-gray-900' : 'text-gray-500' }}">
                                {{ $item->judul }}
                            </p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $tipeConfig['badge'] }}">
                                {{ $tipeConfig['label'] }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400 whitespace-nowrap flex-shrink-0">
                            {{ $item->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm mt-1 {{ $belumDibaca ? 'text-gray-700' : 'text-gray-400' }}">
                        {{ $item->pesan }}
                    </p>
                </div>

                {{-- Tombol tandai dibaca --}}
                @if ($belumDibaca)
                    <div class="flex-shrink-0">
                        <form method="POST" action="{{ route('notifikasi.read', $item) }}">
                            @csrf
                            <button type="submit"
                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium whitespace-nowrap transition">
                                Tandai Dibaca
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white border border-gray-200 rounded-xl px-6 py-16 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <p class="text-gray-500 text-sm">Tidak ada notifikasi.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($notifikasi->hasPages())
        <div class="mt-4">
            {{ $notifikasi->links() }}
        </div>
    @endif

</div>
@endsection
