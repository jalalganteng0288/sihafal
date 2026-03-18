<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Evaluasi;
use App\Models\Notifikasi;
use App\Models\Setoran;
use App\Models\Surah;
use App\Services\EvaluasiService;
use App\Services\HafalanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetoranController extends Controller
{
    public function __construct(
        private readonly HafalanService $hafalanService,
        private readonly EvaluasiService $evaluasiService,
    ) {}

    public function index(Request $request)
    {
        $ustadz = Auth::user()->ustadz;

        // Ambil semua halaqah milik ustadz ini
        $halaqahIds = $ustadz->halaqah()->pluck('id');

        // Ambil santri_id dari halaqah ustadz
        $santriIds = \App\Models\Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        $query = Setoran::with(['santri', 'surah', 'evaluasi'])
            ->whereIn('santri_id', $santriIds);

        // Filter tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_setoran', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_setoran', '<=', $request->tanggal_sampai);
        }

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter surah
        if ($request->filled('surah_id')) {
            $query->where('surah_id', $request->surah_id);
        }

        $setoran = $query->orderByDesc('tanggal_setoran')->paginate(15)->withQueryString();

        $surahList = Surah::orderBy('nomor_surah')->get();

        return view('ustadz.setoran.index', compact('setoran', 'surahList'));
    }

    public function create()
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');

        $santriList = \App\Models\Santri::whereIn('halaqah_id', $halaqahIds)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        $surahList = Surah::orderBy('nomor_surah')->get();

        return view('ustadz.setoran.create', compact('santriList', 'surahList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'santri_id'         => 'required|exists:santri,id',
            'tanggal_setoran'   => 'required|date',
            'jenis'             => 'required|in:setoran_baru,murajaah',
            'surah_id'          => 'required|exists:surah,id',
            'ayat_awal'         => 'required|integer|min:1',
            'ayat_akhir'        => 'required|integer|min:1',
            'nilai_kelancaran'  => 'required|numeric|min:0|max:100',
            'nilai_tajwid'      => 'required|numeric|min:0|max:100',
            'nilai_makhraj'     => 'required|numeric|min:0|max:100',
            'catatan'           => 'nullable|string',
        ]);

        // Validasi rentang ayat
        if (! $this->hafalanService->validasiRentangAyat(
            (int) $validated['surah_id'],
            (int) $validated['ayat_awal'],
            (int) $validated['ayat_akhir']
        )) {
            return back()->withInput()->withErrors([
                'ayat_akhir' => 'Rentang ayat tidak valid untuk surah yang dipilih.',
            ]);
        }

        $ustadz = Auth::user()->ustadz;

        $jumlahAyat = (int) $validated['ayat_akhir'] - (int) $validated['ayat_awal'] + 1;

        // Simpan setoran
        $setoran = Setoran::create([
            'santri_id'           => $validated['santri_id'],
            'ustadz_id'           => $ustadz->id,
            'tanggal_setoran'     => $validated['tanggal_setoran'],
            'jenis'               => $validated['jenis'],
            'surah_id'            => $validated['surah_id'],
            'ayat_awal'           => $validated['ayat_awal'],
            'ayat_akhir'          => $validated['ayat_akhir'],
            'jumlah_ayat_disetor' => $jumlahAyat,
            'catatan'             => $validated['catatan'] ?? null,
        ]);

        // Hitung nilai akhir dan klasifikasi
        $nilaiAkhir = $this->evaluasiService->hitungNilaiAkhir(
            (float) $validated['nilai_kelancaran'],
            (float) $validated['nilai_tajwid'],
            (float) $validated['nilai_makhraj']
        );
        $kategori = $this->evaluasiService->klasifikasiNilai($nilaiAkhir);

        // Simpan evaluasi
        Evaluasi::create([
            'setoran_id'       => $setoran->id,
            'nilai_kelancaran' => $validated['nilai_kelancaran'],
            'nilai_tajwid'     => $validated['nilai_tajwid'],
            'nilai_makhraj'    => $validated['nilai_makhraj'],
            'nilai_akhir'      => $nilaiAkhir,
            'kategori'         => $kategori,
            'catatan_evaluasi' => $validated['catatan'] ?? null,
        ]);

        // Kirim notifikasi ke santri
        $santri = $setoran->santri()->with('user')->first();
        if ($santri && $santri->user) {
            $surahNama = $setoran->surah?->nama_latin ?? 'Surah';
            $jenisLabel = $validated['jenis'] === 'setoran_baru' ? 'Setoran Baru' : "Muraja'ah";
            Notifikasi::create([
                'user_id'   => $santri->user->id,
                'judul'     => 'Setoran Hafalan Dicatat',
                'pesan'     => "{$jenisLabel} {$surahNama} ayat {$validated['ayat_awal']}–{$validated['ayat_akhir']} telah dicatat. Nilai akhir: {$nilaiAkhir} ({$kategori}).",
                'tipe'      => 'sistem',
                'is_dibaca' => false,
            ]);
        }

        return redirect()->route('ustadz.setoran.index')
            ->with('success', 'Setoran berhasil dicatat.');
    }

    public function edit(Setoran $setoran)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = \App\Models\Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        // Pastikan setoran milik santri di halaqah ustadz
        abort_unless($santriIds->contains($setoran->santri_id), 403);

        $setoran->load(['evaluasi', 'surah', 'santri']);

        $santriList = \App\Models\Santri::whereIn('halaqah_id', $halaqahIds)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        $surahList = Surah::orderBy('nomor_surah')->get();

        return view('ustadz.setoran.edit', compact('setoran', 'santriList', 'surahList'));
    }

    public function update(Request $request, Setoran $setoran)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = \App\Models\Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        abort_unless($santriIds->contains($setoran->santri_id), 403);

        $validated = $request->validate([
            'santri_id'         => 'required|exists:santri,id',
            'tanggal_setoran'   => 'required|date',
            'jenis'             => 'required|in:setoran_baru,murajaah',
            'surah_id'          => 'required|exists:surah,id',
            'ayat_awal'         => 'required|integer|min:1',
            'ayat_akhir'        => 'required|integer|min:1',
            'nilai_kelancaran'  => 'required|numeric|min:0|max:100',
            'nilai_tajwid'      => 'required|numeric|min:0|max:100',
            'nilai_makhraj'     => 'required|numeric|min:0|max:100',
            'catatan'           => 'nullable|string',
        ]);

        // Validasi rentang ayat
        if (! $this->hafalanService->validasiRentangAyat(
            (int) $validated['surah_id'],
            (int) $validated['ayat_awal'],
            (int) $validated['ayat_akhir']
        )) {
            return back()->withInput()->withErrors([
                'ayat_akhir' => 'Rentang ayat tidak valid untuk surah yang dipilih.',
            ]);
        }

        $jumlahAyat = (int) $validated['ayat_akhir'] - (int) $validated['ayat_awal'] + 1;

        $setoran->update([
            'santri_id'           => $validated['santri_id'],
            'tanggal_setoran'     => $validated['tanggal_setoran'],
            'jenis'               => $validated['jenis'],
            'surah_id'            => $validated['surah_id'],
            'ayat_awal'           => $validated['ayat_awal'],
            'ayat_akhir'          => $validated['ayat_akhir'],
            'jumlah_ayat_disetor' => $jumlahAyat,
            'catatan'             => $validated['catatan'] ?? null,
        ]);

        $nilaiAkhir = $this->evaluasiService->hitungNilaiAkhir(
            (float) $validated['nilai_kelancaran'],
            (float) $validated['nilai_tajwid'],
            (float) $validated['nilai_makhraj']
        );
        $kategori = $this->evaluasiService->klasifikasiNilai($nilaiAkhir);

        $setoran->evaluasi()->updateOrCreate(
            ['setoran_id' => $setoran->id],
            [
                'nilai_kelancaran' => $validated['nilai_kelancaran'],
                'nilai_tajwid'     => $validated['nilai_tajwid'],
                'nilai_makhraj'    => $validated['nilai_makhraj'],
                'nilai_akhir'      => $nilaiAkhir,
                'kategori'         => $kategori,
                'catatan_evaluasi' => $validated['catatan'] ?? null,
            ]
        );

        return redirect()->route('ustadz.setoran.index')
            ->with('success', 'Setoran berhasil diperbarui.');
    }
}
