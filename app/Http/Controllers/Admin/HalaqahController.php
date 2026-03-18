<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Halaqah;
use App\Models\Ustadz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HalaqahController extends Controller
{
    public function index()
    {
        $halaqah = Halaqah::with(['ustadz', 'santri'])
            ->withCount('santri')
            ->orderBy('nama')
            ->paginate(15);

        return view('admin.halaqah.index', compact('halaqah'));
    }

    public function create()
    {
        $ustadz = Ustadz::orderBy('nama_lengkap')->get();
        return view('admin.halaqah.create', compact('ustadz'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'ustadz_id'  => 'nullable|exists:ustadz,id',
        ]);

        DB::transaction(function () use ($validated) {
            $halaqah = Halaqah::create($validated);

            // Update hak akses ustadz — pastikan role ustadz aktif
            if (!empty($validated['ustadz_id'])) {
                $ustadz = Ustadz::find($validated['ustadz_id']);
                if ($ustadz && $ustadz->user) {
                    $ustadz->user->update(['role' => 'ustadz', 'is_active' => true]);
                }
            }
        });

        return redirect()->route('admin.halaqah.index')
            ->with('success', 'Halaqah berhasil ditambahkan.');
    }

    public function edit(Halaqah $halaqah)
    {
        $ustadz = Ustadz::orderBy('nama_lengkap')->get();
        return view('admin.halaqah.edit', compact('halaqah', 'ustadz'));
    }

    public function update(Request $request, Halaqah $halaqah)
    {
        $validated = $request->validate([
            'nama'       => 'required|string|max:255',
            'ustadz_id'  => 'nullable|exists:ustadz,id',
        ]);

        DB::transaction(function () use ($validated, $halaqah) {
            $halaqah->update($validated);

            // Update hak akses ustadz baru
            if (!empty($validated['ustadz_id'])) {
                $ustadz = Ustadz::find($validated['ustadz_id']);
                if ($ustadz && $ustadz->user) {
                    $ustadz->user->update(['role' => 'ustadz', 'is_active' => true]);
                }
            }
        });

        return redirect()->route('admin.halaqah.index')
            ->with('success', 'Halaqah berhasil diperbarui.');
    }
}
