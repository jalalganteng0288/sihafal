@extends('layouts.app')

@section('title', 'Edit Ustadz')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.ustadz.index') }}"
           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Ustadz</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data ustadz: <span class="font-medium text-gray-700">{{ $ustadz->nama_lengkap }}</span></p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.ustadz.update', $ustadz) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    id="nama_lengkap"
                    type="text"
                    name="nama_lengkap"
                    value="{{ old('nama_lengkap', $ustadz->nama_lengkap) }}"
                    placeholder="Masukkan nama lengkap ustadz"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('nama_lengkap') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('nama_lengkap')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Identitas --}}
            <div>
                <label for="nomor_identitas" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nomor Identitas <span class="text-red-500">*</span>
                </label>
                <input
                    id="nomor_identitas"
                    type="text"
                    name="nomor_identitas"
                    value="{{ old('nomor_identitas', $ustadz->nomor_identitas) }}"
                    placeholder="Contoh: UST2024001"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('nomor_identitas') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('nomor_identitas')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Spesialisasi --}}
            <div>
                <label for="spesialisasi" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Spesialisasi
                </label>
                <input
                    id="spesialisasi"
                    type="text"
                    name="spesialisasi"
                    value="{{ old('spesialisasi', $ustadz->spesialisasi) }}"
                    placeholder="Contoh: Tahfidz Juz 30, Tajwid"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('spesialisasi') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('spesialisasi')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ubah Password --}}
            <div class="border-t border-gray-100 pt-5">
                <p class="text-sm font-semibold text-gray-700 mb-3">Ubah Password (Opsional)</p>
                <div>
                    <label for="password_baru" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Password Baru
                    </label>
                    <input
                        id="password_baru"
                        type="password"
                        name="password_baru"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('password_baru') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('password_baru')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1.5 text-xs text-gray-400">Ustadz akan mendapat notifikasi jika password diubah.</p>
                </div>
            </div>

            {{-- Halaqah Diampu (info only) --}}
            @if ($ustadz->halaqah->isNotEmpty())
                <div class="p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 mb-2">Halaqah yang Diampu</p>
                    <div class="flex flex-wrap gap-1">
                        @foreach ($ustadz->halaqah as $h)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $h->nama }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Untuk mengubah penugasan halaqah, kelola melalui menu Manajemen Halaqah.</p>
                </div>
            @endif

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition duration-150 shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.ustadz.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
