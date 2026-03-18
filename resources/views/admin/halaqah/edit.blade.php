@extends('layouts.app')

@section('title', 'Edit Halaqah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.halaqah.index') }}"
           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Halaqah</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data halaqah: <span class="font-medium text-gray-700">{{ $halaqah->nama }}</span></p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.halaqah.update', $halaqah) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama Halaqah --}}
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Halaqah <span class="text-red-500">*</span>
                </label>
                <input
                    id="nama"
                    type="text"
                    name="nama"
                    value="{{ old('nama', $halaqah->nama) }}"
                    placeholder="Contoh: Halaqah Al-Fatihah"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('nama')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ustadz --}}
            <div>
                <label for="ustadz_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Ustadz Pengampu
                </label>
                <select
                    id="ustadz_id"
                    name="ustadz_id"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('ustadz_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="">— Belum ditentukan —</option>
                    @foreach ($ustadz as $u)
                        <option value="{{ $u->id }}" {{ old('ustadz_id', $halaqah->ustadz_id) == $u->id ? 'selected' : '' }}>
                            {{ $u->nama_lengkap }}
                            @if ($u->spesialisasi)
                                — {{ $u->spesialisasi }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('ustadz_id')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Santri --}}
            <div class="p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700">Jumlah Santri</p>
                <p class="text-2xl font-bold text-green-600 mt-1">{{ $halaqah->santri->count() }}</p>
                <p class="text-xs text-gray-400 mt-0.5">santri terdaftar di halaqah ini</p>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition duration-150 shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.halaqah.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
