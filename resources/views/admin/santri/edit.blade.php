@extends('layouts.app')

@section('title', 'Edit Santri')

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
            <h1 class="text-2xl font-bold text-gray-900">Edit Santri</h1>
            <p class="text-sm text-gray-500 mt-0.5">Perbarui data santri: <span class="font-medium text-gray-700">{{ $santri->nama_lengkap }}</span></p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form method="POST" action="{{ route('admin.santri.update', $santri) }}" class="space-y-5">
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
                    value="{{ old('nama_lengkap', $santri->nama_lengkap) }}"
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
                    value="{{ old('nomor_induk', $santri->nomor_induk) }}"
                    placeholder="Contoh: 2024001"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('nomor_induk') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
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
                        value="{{ old('tanggal_lahir', $santri->tanggal_lahir?->format('Y-m-d')) }}"
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
                        <option value="L" {{ old('jenis_kelamin', $santri->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $santri->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
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
                        <option value="{{ $h->id }}" {{ old('halaqah_id', $santri->halaqah_id) == $h->id ? 'selected' : '' }}>
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
                    value="{{ old('tanggal_masuk', $santri->tanggal_masuk?->format('Y-m-d')) }}"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('tanggal_masuk') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                >
                @error('tanggal_masuk')
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
                    <p class="mt-1.5 text-xs text-gray-400">Santri akan mendapat notifikasi jika password diubah.</p>
                </div>
            </div>

            {{-- Status --}}
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-700">Status Santri</p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Untuk menonaktifkan santri, gunakan tombol Nonaktifkan di halaman daftar.
                    </p>
                </div>
                <span class="{{ $santri->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }} inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                    {{ $santri->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition duration-150 shadow-sm">
                    Simpan Perubahan
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
