<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use App\Models\Ustadz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UstadzController extends Controller
{
    public function index()
    {
        $ustadz = Ustadz::with(['halaqah', 'user'])
            ->orderBy('nama_lengkap')
            ->paginate(15);

        return view('admin.ustadz.index', compact('ustadz'));
    }

    public function create()
    {
        return view('admin.ustadz.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nomor_identitas' => 'required|string|unique:ustadz,nomor_identitas',
            'spesialisasi'    => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            // Buat akun user (username = nomor_identitas, password = nomor_identitas)
            $user = User::create([
                'name'      => $validated['nama_lengkap'],
                'username'  => $validated['nomor_identitas'],
                'email'     => $validated['nomor_identitas'] . '@ustadz.sihafal.id',
                'password'  => Hash::make($validated['nomor_identitas']),
                'role'      => 'ustadz',
                'is_active' => true,
            ]);

            // Buat data ustadz
            Ustadz::create([
                'user_id'         => $user->id,
                'nomor_identitas' => $validated['nomor_identitas'],
                'nama_lengkap'    => $validated['nama_lengkap'],
                'spesialisasi'    => $validated['spesialisasi'] ?? null,
            ]);
        });

        return redirect()->route('admin.ustadz.index')
            ->with('success', 'Data ustadz berhasil ditambahkan.');
    }

    public function edit(Ustadz $ustadz)
    {
        return view('admin.ustadz.edit', compact('ustadz'));
    }

    public function update(Request $request, Ustadz $ustadz)
    {
        $validated = $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nomor_identitas' => ['required', 'string', Rule::unique('ustadz', 'nomor_identitas')->ignore($ustadz->id)],
            'spesialisasi'    => 'nullable|string|max:255',
            'password_baru'   => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($validated, $ustadz) {
            $ustadz->update([
                'nama_lengkap'    => $validated['nama_lengkap'],
                'nomor_identitas' => $validated['nomor_identitas'],
                'spesialisasi'    => $validated['spesialisasi'] ?? null,
            ]);

            // Update nama di tabel users juga
            if ($ustadz->user) {
                $userUpdate = ['name' => $validated['nama_lengkap']];

                // Jika admin mengisi password baru, hash dan simpan
                if (! empty($validated['password_baru'])) {
                    $userUpdate['password'] = Hash::make($validated['password_baru']);

                    // Kirim notifikasi ke ustadz bahwa password telah diubah
                    Notifikasi::create([
                        'user_id'   => $ustadz->user->id,
                        'judul'     => 'Password Akun Diubah',
                        'pesan'     => 'Password akun Anda telah diubah oleh admin. Silakan gunakan password baru untuk login.',
                        'tipe'      => 'perubahan_password',
                        'is_dibaca' => false,
                    ]);
                }

                $ustadz->user->update($userUpdate);
            }
        });

        return redirect()->route('admin.ustadz.index')
            ->with('success', 'Data ustadz berhasil diperbarui.');
    }
}
