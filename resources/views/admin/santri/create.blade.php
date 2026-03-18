@extends('layouts.app')

@section('title', 'Tambah Santri')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.santri.index') }}"
           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Santri</h1>
            <p class="text-sm text-gray-500 mt-0.5">Isi data santri baru di bawah ini</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.santri.store') }}" class="space-y-5">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    id="nama_lengkap"
                    type="text"
                    name="nama_lengkap"
                    value="{{ old('nama_lengkap') }}"
                    placeholder="Masukkan nama lengkap santri"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('nama_lengkap') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('nama_lengkap')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor Induk --}}
            <div>
                <label for="nomor_induk" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Nomor Induk <span class="text-red-500">*</span>
                </label>
                <input
                    id="nomor_induk"
                    type="text"
                    name="nomor_induk"
                    value="{{ old('nomor_induk') }}"
                    placeholder="Contoh: 2024001"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('nomor_induk') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                <p class="mt-1 text-xs text-gray-400">Nomor induk akan digunakan sebagai username dan password awal.</p>
                @error('nomor_induk')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Lahir & Jenis Kelamin --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="tanggal_lahir"
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('tanggal_lahir') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                    @error('tanggal_lahir')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="jenis_kelamin"
                        name="jenis_kelamin"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('jenis_kelamin') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    >
                        <option value="">Pilih...</option>
                        <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Halaqah --}}
            <div>
                <label for="halaqah_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Halaqah
                </label>
                <select
                    id="halaqah_id"
                    name="halaqah_id"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('halaqah_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                    <option value="">— Belum ditentukan —</option>
                    @foreach ($halaqah as $h)
                        <option value="{{ $h->id }}" {{ old('halaqah_id') == $h->id ? 'selected' : '' }}>
                            {{ $h->nama }}
                        </option>
                    @endforeach
                </select>
                @error('halaqah_id')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Masuk --}}
            <div>
                <label for="tanggal_masuk" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Tanggal Masuk <span class="text-red-500">*</span>
                </label>
                <input
                    id="tanggal_masuk"
                    type="date"
                    name="tanggal_masuk"
                    value="{{ old('tanggal_masuk') }}"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('tanggal_masuk') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('tanggal_masuk')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition duration-150 shadow-sm">
                    Simpan
                </button>
                <a href="{{ route('admin.santri.index') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition duration-150">
                    Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
