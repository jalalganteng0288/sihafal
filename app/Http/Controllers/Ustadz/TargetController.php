<?php

namespace App\Http\Controllers\Ustadz;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Target;
use App\Services\HafalanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetController extends Controller
{
    public function __construct(private HafalanService $hafalanService) {}

    public function index()
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriList = Santri::whereIn('halaqah_id', $halaqahIds)
            ->where('is_active', true)
            ->orderBy('nama_lengkap')
            ->get();

        $santriIds = $santriList->pluck('id');

        $targets = Target::with(['santri'])
            ->whereIn('santri_id', $santriIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ustadz.target.index', compact('targets', 'santriList'));
    }

    public function store(Request $request)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        $validated = $request->validate([
            'santri_id'   => ['required', 'exists:santri,id'],
            'target_juz'  => ['required', 'numeric', 'min:0.5', 'max:30'],
            'batas_waktu' => ['required', 'date', 'after:today'],
            'catatan'     => ['nullable', 'string', 'max:500'],
        ]);

        abort_unless($santriIds->contains($validated['santri_id']), 403);

        $target = Target::create([
            'santri_id'            => $validated['santri_id'],
            'ustadz_id'            => $ustadz->id,
            'target_juz'           => $validated['target_juz'],
            'batas_waktu'          => $validated['batas_waktu'],
            'catatan'              => $validated['catatan'] ?? null,
            'persentase_pencapaian' => 0,
            'is_tercapai'          => false,
        ]);

        $persentase = $this->hafalanService->hitungPersentaseTarget($target->santri_id, $target->id);
        $target->update([
            'persentase_pencapaian' => $persentase,
            'is_tercapai'           => $persentase >= 100,
        ]);

        return redirect()->route('ustadz.target.index')
            ->with('success', 'Target hafalan berhasil ditambahkan.');
    }

    public function update(Request $request, Target $target)
    {
        $ustadz = Auth::user()->ustadz;

        $halaqahIds = $ustadz->halaqah()->pluck('id');
        $santriIds  = Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');

        abort_unless($santriIds->contains($target->santri_id), 403);

        $validated = $request->validate([
            'target_juz'  => ['required', 'numeric', 'min:0.5', 'max:30'],
            'batas_waktu' => ['required', 'date'],
            'catatan'     => ['nullable', 'string', 'max:500'],
        ]);

        $target->update([
            'target_juz'  => $validated['target_juz'],
            'batas_waktu' => $validated['batas_waktu'],
            'catatan'     => $validated['catatan'] ?? null,
        ]);

        $persentase = $this->hafalanService->hitungPersentaseTarget($target->santri_id, $target->id);
        $target->update([
            'persentase_pencapaian' => $persentase,
            'is_tercapai'           => $persentase >= 100,
        ]);

        return redirect()->route('ustadz.target.index')
            ->with('success', 'Target hafalan berhasil diperbarui.');
    }
}
