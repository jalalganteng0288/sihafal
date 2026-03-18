@extends('layouts.app')

@section('title', 'Tambah Setoran')

@section('content')
<div class="max-w-3xl mx-auto space-y-6"
     x-data="{
         kelancaran: {{ old('nilai_kelancaran', 80) }},
         tajwid: {{ old('nilai_tajwid', 80) }},
         makhraj: {{ old('nilai_makhraj', 80) }},
         get nilaiAkhir() {
             return ((parseFloat(this.kelancaran) + parseFloat(this.tajwid) + parseFloat(this.makhraj)) / 3).toFixed(2);
         },
         get kategori() {
             const n = parseFloat(this.nilaiAkhir);
             if (n >= 90) return { label: 'Mumtaz', color: 'bg-green-100 text-green-800' };
             if (n >= 80) return { label: 'Jayyid Jiddan', color: 'bg-blue-100 text-blue-800' };
             if (n >= 70) return { label: 'Jayyid', color: 'bg-cyan-100 text-cyan-800' };
             if (n >= 60) return { label: 'Maqbul', color: 'bg-yellow-100 text-yellow-800' };
             return { label: 'Dhaif', color: 'bg-red-100 text-red-800' };
         }
     }">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('ustadz.setoran.index') }}"
           class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition duration-150">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tambah Setoran</h1>
            <p class="text-sm text-gray-500 mt-0.5">Catat setoran hafalan santri</p>
        </div>
    </div>

    <form method="POST" action="{{ route('ustadz.setoran.store') }}" class="space-y-5">
        @csrf

        {{-- Data Setoran --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Data Setoran</h2>

            {{-- Santri --}}
            <div>
                <label for="santri_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Santri <span class="text-red-500">*</span>
                </label>
                <select id="santri_id" name="santri_id"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('santri_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">— Pilih Santri —</option>
                    @foreach ($santriList as $santri)
                        <option value="{{ $santri->id }}" {{ old('santri_id') == $santri->id ? 'selected' : '' }}>
                            {{ $santri->nama_lengkap }}
                        </option>
                    @endforeach
                </select>
                @error('santri_id')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal & Jenis --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="tanggal_setoran" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tanggal Setoran <span class="text-red-500">*</span>
                    </label>
                    <input id="tanggal_setoran" type="date" name="tanggal_setoran"
                        value="{{ old('tanggal_setoran', date('Y-m-d')) }}"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('tanggal_setoran') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('tanggal_setoran')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Jenis <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis" name="jenis"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('jenis') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">— Pilih Jenis —</option>
                        <option value="setoran_baru" {{ old('jenis') === 'setoran_baru' ? 'selected' : '' }}>Setoran Baru</option>
                        <option value="murajaah" {{ old('jenis') === 'murajaah' ? 'selected' : '' }}>Murajaah</option>
                    </select>
                    @error('jenis')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Surah --}}
            <div>
                <label for="surah_id" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Surah <span class="text-red-500">*</span>
                </label>
                <select id="surah_id" name="surah_id"
                    class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('surah_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">— Pilih Surah —</option>
                    @foreach ($surahList as $surah)
                        <option value="{{ $surah->id }}" {{ old('surah_id') == $surah->id ? 'selected' : '' }}>
                            {{ $surah->nomor_surah }}. {{ $surah->nama_latin }} ({{ $surah->jumlah_ayat }} ayat)
                        </option>
                    @endforeach
                </select>
                @error('surah_id')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ayat Awal & Akhir --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="ayat_awal" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ayat Awal <span class="text-red-500">*</span>
                    </label>
                    <input id="ayat_awal" type="number" name="ayat_awal" min="1"
                        value="{{ old('ayat_awal') }}"
                        placeholder="1"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('ayat_awal') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('ayat_awal')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="ayat_akhir" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Ayat Akhir <span class="text-red-500">*</span>
                    </label>
                    <input id="ayat_akhir" type="number" name="ayat_akhir" min="1"
                        value="{{ old('ayat_akhir') }}"
                        placeholder="7"
                        class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('ayat_akhir') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('ayat_akhir')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Evaluasi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-5">
            <h2 class="text-base font-semibold text-gray-800 border-b border-gray-100 pb-3">Evaluasi</h2>

            {{-- Nilai Kelancaran --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="nilai_kelancaran" class="text-sm font-medium text-gray-700">
                        Kelancaran <span class="text-red-500">*</span>
                    </label>
                    <span class="text-sm font-semibold text-green-700" x-text="kelancaran"></span>
                </div>
                <input id="nilai_kelancaran" type="range" name="nilai_kelancaran"
                    min="0" max="100" step="1"
                    x-model="kelancaran"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                @error('nilai_kelancaran')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nilai Tajwid --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="nilai_tajwid" class="text-sm font-medium text-gray-700">
                        Tajwid <span class="text-red-500">*</span>
                    </label>
                    <span class="text-sm font-semibold text-green-700" x-text="tajwid"></span>
                </div>
                <input id="nilai_tajwid" type="range" name="nilai_tajwid"
                    min="0" max="100" step="1"
                    x-model="tajwid"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                @error('nilai_tajwid')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nilai Makhraj --}}
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="nilai_makhraj" class="text-sm font-medium text-gray-700">
                        Makhraj <span class="text-red-500">*</span>
                    </label>
                    <span class="text-sm font-semibold text-green-700" x-text="makhraj"></span>
                </div>
                <input id="nilai_makhraj" type="range" name="nilai_makhraj"
                    min="0" max="100" step="1"
                    x-model="makhraj"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-green-600">
                @error('nilai_makhraj')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Preview Nilai Akhir --}}
            <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                <span class="text-sm font-medium text-gray-700">Nilai Akhir (Preview)</span>
                <div class="flex items-center gap-3">
                    <span class="text-xl font-bold text-gray-900" x-text="nilaiAkhir"></span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          :class="kategori.color"
                          x-text="kategori.label"></span>
                </div>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <label for="catatan" class="block text-sm font-medium text-gray-700 mb-1.5">Catatan</label>
            <textarea id="catatan" name="catatan" rows="3"
                placeholder="Catatan tambahan (opsional)..."
                class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 {{ $errors->has('catatan') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('catatan') }}</textarea>
            @error('catatan')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol --}}
        <div class="flex gap-3">
            <button type="submit"
                    class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition duration-150 shadow-sm">
                Simpan Setoran
            </button>
            <a href="{{ route('ustadz.setoran.index') }}"
               class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition duration-150">
                Batal
            </a>
        </div>
    </form>

</div>
@endsection
