<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Halaqah;
use App\Models\Notifikasi;
use App\Models\Santri;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SantriController extends Controller
{
    public function index(Request $request)
    {
        $query = Santri::with(['halaqah', 'user']);

        // Pencarian berdasarkan nama atau nomor induk
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nomor_induk', 'like', "%{$search}%");
            });
        }

        // Pengurutan
        $sort = $request->input('sort', 'nama_lengkap');
        $direction = $request->input('direction', 'asc');

        if ($sort === 'halaqah') {
            $query->leftJoin('halaqah', 'santri.halaqah_id', '=', 'halaqah.id')
                  ->orderBy('halaqah.nama', $direction)
                  ->select('santri.*');
        } elseif ($sort === 'hafalan') {
            // Urutkan berdasarkan total ayat hafalan (subquery)
            $query->withSum([
                'setoran as total_hafalan' => fn ($q) => $q->where('jenis', 'setoran_baru')
            ], 'jumlah_ayat_disetor')
            ->orderBy('total_hafalan', $direction);
        } else {
            $query->orderBy('nama_lengkap', $direction);
        }

        $santri = $query->paginate(15)->withQueryString();

        return view('admin.santri.index', compact('santri', 'search', 'sort', 'direction'));
    }

    public function create()
    {
        $halaqah = Halaqah::orderBy('nama')->get();
        return view('admin.santri.create', compact('halaqah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nomor_induk'    => 'required|string|unique:santri,nomor_induk',
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'halaqah_id'     => 'nullable|exists:halaqah,id',
            'tanggal_masuk'  => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {
            // Buat akun user (username = nomor_induk, password = nomor_induk)
            $user = User::create([
                'name'      => $validated['nama_lengkap'],
                'username'  => $validated['nomor_induk'],
                'email'     => $validated['nomor_induk'] . '@santri.sihafal.id',
                'password'  => Hash::make($validated['nomor_induk']),
                'role'      => 'santri',
                'is_active' => true,
            ]);

            // Buat data santri
            Santri::create([
                'user_id'        => $user->id,
                'nomor_induk'    => $validated['nomor_induk'],
                'nama_lengkap'   => $validated['nama_lengkap'],
                'tanggal_lahir'  => $validated['tanggal_lahir'],
                'jenis_kelamin'  => $validated['jenis_kelamin'],
                'halaqah_id'     => $validated['halaqah_id'] ?? null,
                'tanggal_masuk'  => $validated['tanggal_masuk'],
                'is_active'      => true,
            ]);
        });

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil ditambahkan.');
    }

    public function edit(Santri $santri)
    {
        $halaqah = Halaqah::orderBy('nama')->get();
        return view('admin.santri.edit', compact('santri', 'halaqah'));
    }

    public function update(Request $request, Santri $santri)
    {
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:255',
            'nomor_induk'    => ['required', 'string', Rule::unique('santri', 'nomor_induk')->ignore($santri->id)],
            'tanggal_lahir'  => 'required|date',
            'jenis_kelamin'  => 'required|in:L,P',
            'halaqah_id'     => 'nullable|exists:halaqah,id',
            'tanggal_masuk'  => 'required|date',
            'password_baru'  => 'nullable|string|min:6',
        ]);

        DB::transaction(function () use ($validated, $santri, $request) {
            $santri->update([
                'nama_lengkap'  => $validated['nama_lengkap'],
                'nomor_induk'   => $validated['nomor_induk'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'halaqah_id'    => $validated['halaqah_id'] ?? null,
                'tanggal_masuk' => $validated['tanggal_masuk'],
            ]);

            // Update nama di tabel users juga
            if ($santri->user) {
                $userUpdate = ['name' => $validated['nama_lengkap']];

                // Jika admin mengisi password baru, hash dan simpan
                if (! empty($validated['password_baru'])) {
                    $userUpdate['password'] = Hash::make($validated['password_baru']);

                    // Kirim notifikasi ke santri bahwa password telah diubah
                    Notifikasi::create([
                        'user_id'   => $santri->user->id,
                        'judul'     => 'Password Akun Diubah',
                        'pesan'     => 'Password akun Anda telah diubah oleh admin. Silakan gunakan password baru untuk login.',
                        'tipe'      => 'perubahan_password',
                        'is_dibaca' => false,
                    ]);
                }

                $santri->user->update($userUpdate);
            }
        });

        return redirect()->route('admin.santri.index')
            ->with('success', 'Data santri berhasil diperbarui.');
    }

    public function deactivate(Santri $santri)
    {
        DB::transaction(function () use ($santri) {
            $santri->update(['is_active' => false]);

            // Nonaktifkan akun user juga
            if ($santri->user) {
                $santri->user->update(['is_active' => false]);
            }
        });

        return redirect()->route('admin.santri.index')
            ->with('success', 'Santri berhasil dinonaktifkan.');
    }
}
