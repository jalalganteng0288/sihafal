@extends('layouts.app')

@section('title', 'Laporan Hafalan')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Laporan Hafalan</h1>
        <p class="text-sm text-gray-500 mt-1">Generate laporan hafalan individu santri atau rekap halaqah.</p>
    </div>

    {{-- Flash Info --}}
    @if (session('info'))
        <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    {{-- Form --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6"
         x-data="{ jenis: 'individu' }">

        <h2 class="text-base font-semibold text-gray-800 mb-5">Pilih Parameter Laporan</h2>

        {{-- Jenis Laporan --}}
        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="jenis_toggle" value="individu"
                           x-model="jenis"
                           class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                    <span class="text-sm text-gray-700">Laporan Individu</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="jenis_toggle" value="halaqah"
                           x-model="jenis"
                           class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500">
                    <span class="text-sm text-gray-700">Rekap Halaqah</span>
                </label>
            </div>
        </div>

        {{-- Form PDF --}}
        <form method="POST" action="{{ route('laporan.pdf') }}" class="space-y-5" id="form-pdf">
            @csrf
            <input type="hidden" name="jenis" :value="jenis">

            {{-- Dropdown Santri (individu) --}}
            <div x-show="jenis === 'individu'" x-cloak>
                <label for="santri_id_pdf" class="block text-sm font-medium text-gray-700 mb-1">
                    Santri <span class="text-red-500">*</span>
                </label>
                <select name="santri_id" id="santri_id_pdf"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Pilih Santri --</option>
                    @foreach ($santriList as $santri)
                        <option value="{{ $santri->id }}">
                            {{ $santri->nama_lengkap }} ({{ $santri->nomor_induk }})
                            @if ($santri->halaqah)
                                — {{ $santri->halaqah->nama }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('santri_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dropdown Halaqah (halaqah) --}}
            <div x-show="jenis === 'halaqah'" x-cloak>
                <label for="halaqah_id_pdf" class="block text-sm font-medium text-gray-700 mb-1">
                    Halaqah <span class="text-red-500">*</span>
                </label>
                <select name="halaqah_id" id="halaqah_id_pdf"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">-- Pilih Halaqah --</option>
                    @foreach ($halaqahList as $halaqah)
                        <option value="{{ $halaqah->id }}">
                            {{ $halaqah->nama }}
                            @if ($halaqah->ustadz)
                                — {{ $halaqah->ustadz->nama_lengkap }}
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('halaqah_id')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Periode --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="periode_awal_pdf" class="block text-sm font-medium text-gray-700 mb-1">
                        Periode Awal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_awal" id="periode_awal_pdf"
                           value="{{ old('periode_awal') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('periode_awal')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="periode_akhir_pdf" class="block text-sm font-medium text-gray-700 mb-1">
                        Periode Akhir <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="periode_akhir" id="periode_akhir_pdf"
                           value="{{ old('periode_akhir') }}"
                           class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('periode_akhir')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Tombol Export --}}
            <div class="flex flex-wrap gap-3 pt-2">
                {{-- PDF --}}
                <button type="submit" form="form-pdf"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Export PDF
                </button>

                {{-- Excel --}}
                <button type="submit" form="form-excel"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Excel
                </button>
            </div>
        </form>

        {{-- Form Excel (hidden, shares same fields via JS) --}}
        <form method="POST" action="{{ route('laporan.excel') }}" id="form-excel" class="hidden">
            @csrf
            <input type="hidden" name="jenis" :value="jenis">
        </form>

    </div>
</div>

{{-- Sync form-excel fields from form-pdf on submit --}}
<script>
    document.getElementById('form-excel').addEventListener('submit', function (e) {
        e.preventDefault();
        const pdfForm = document.getElementById('form-pdf');
        const excelForm = this;

        // Copy all inputs from pdf form to excel form
        const fields = ['jenis', 'santri_id', 'halaqah_id', 'periode_awal', 'periode_akhir'];
        fields.forEach(function (name) {
            const existing = excelForm.querySelector('[name="' + name + '"]');
            if (existing && existing.type !== 'hidden') return;

            const source = pdfForm.querySelector('[name="' + name + '"]');
            if (!source) return;

            let hidden = excelForm.querySelector('input[name="' + name + '"]');
            if (!hidden) {
                hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                excelForm.appendChild(hidden);
            }
            hidden.value = source.value;
        });

        excelForm.submit();
    });
</script>
@endsection
