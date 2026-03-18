<?php

namespace App\Http\Controllers;

use App\Exports\LaporanHalaqahExport;
use App\Exports\LaporanIndividuExport;
use App\Models\Halaqah;
use App\Models\Santri;
use App\Services\LaporanService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(
        private readonly LaporanService $laporanService
    ) {}

    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $halaqahList = Halaqah::with('ustadz')->orderBy('nama')->get();
            $santriList  = Santri::active()->with('halaqah')->orderBy('nama_lengkap')->get();
        } else {
            // Ustadz: hanya halaqah miliknya
            $ustadz      = $user->ustadz;
            $halaqahList = $ustadz ? $ustadz->halaqah()->with('ustadz')->orderBy('nama')->get() : collect();
            $halaqahIds  = $halaqahList->pluck('id');
            $santriList  = Santri::active()
                ->whereIn('halaqah_id', $halaqahIds)
                ->with('halaqah')
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('laporan.index', compact('halaqahList', 'santriList'));
    }

    public function exportPdf(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($validated['jenis'] === 'individu') {
            $data = $this->laporanService->generateLaporanIndividu(
                $validated['santri_id'],
                $validated['periode_awal'],
                $validated['periode_akhir']
            );

            if ($data['setoran']->isEmpty()) {
                return back()->with('info', 'Tidak ada data setoran pada periode yang dipilih.');
            }

            $pdf = Pdf::loadView('laporan.pdf-individu', $data)
                ->setPaper('a4', 'landscape');

            $filename = 'laporan-individu-' . $data['santri']->nomor_induk . '-' . $validated['periode_awal'] . '.pdf';

            return $pdf->download($filename);
        } else {
            $data = $this->laporanService->generateLaporanHalaqah(
                $validated['halaqah_id'],
                $validated['periode_awal'],
                $validated['periode_akhir']
            );

            if ($data['santri_list']->isEmpty()) {
                return back()->with('info', 'Tidak ada data santri pada halaqah yang dipilih.');
            }

            $pdf = Pdf::loadView('laporan.pdf-halaqah', $data)
                ->setPaper('a4', 'portrait');

            $filename = 'laporan-halaqah-' . $data['halaqah']->id . '-' . $validated['periode_awal'] . '.pdf';

            return $pdf->download($filename);
        }
    }

    public function exportExcel(Request $request)
    {
        $validated = $this->validateRequest($request);

        if ($validated['jenis'] === 'individu') {
            $data = $this->laporanService->generateLaporanIndividu(
                $validated['santri_id'],
                $validated['periode_awal'],
                $validated['periode_akhir']
            );

            if ($data['setoran']->isEmpty()) {
                return back()->with('info', 'Tidak ada data setoran pada periode yang dipilih.');
            }

            $filename = 'laporan-individu-' . $data['santri']->nomor_induk . '-' . $validated['periode_awal'] . '.xlsx';

            return Excel::download(new LaporanIndividuExport($data), $filename);
        } else {
            $data = $this->laporanService->generateLaporanHalaqah(
                $validated['halaqah_id'],
                $validated['periode_awal'],
                $validated['periode_akhir']
            );

            if ($data['santri_list']->isEmpty()) {
                return back()->with('info', 'Tidak ada data santri pada halaqah yang dipilih.');
            }

            $filename = 'laporan-halaqah-' . $data['halaqah']->id . '-' . $validated['periode_awal'] . '.xlsx';

            return Excel::download(new LaporanHalaqahExport($data), $filename);
        }
    }

    private function validateRequest(Request $request): array
    {
        $user = auth()->user();

        $validated = $request->validate([
            'jenis'        => 'required|in:individu,halaqah',
            'santri_id'    => 'required_if:jenis,individu|nullable|exists:santri,id',
            'halaqah_id'   => 'required_if:jenis,halaqah|nullable|exists:halaqah,id',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        // Pastikan ustadz hanya bisa akses data miliknya
        if ($user->role === 'ustadz') {
            $ustadz     = $user->ustadz;
            $halaqahIds = $ustadz ? $ustadz->halaqah()->pluck('id') : collect();

            if ($validated['jenis'] === 'halaqah' && ! $halaqahIds->contains($validated['halaqah_id'])) {
                abort(403, 'Anda tidak memiliki akses ke halaqah ini.');
            }

            if ($validated['jenis'] === 'individu') {
                $santri = Santri::findOrFail($validated['santri_id']);
                if (! $halaqahIds->contains($santri->halaqah_id)) {
                    abort(403, 'Anda tidak memiliki akses ke santri ini.');
                }
            }
        }

        return $validated;
    }
}
