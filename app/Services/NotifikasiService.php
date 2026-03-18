<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\Santri;
use App\Models\Target;
use App\Models\User;
use Carbon\Carbon;

class NotifikasiService
{
    /**
     * Cek santri yang tidak menyetor dalam $hariThreshold hari terakhir
     * dan buat notifikasi untuk ustadz yang mengampu.
     */
    public function cekSantriTidakSetor(int $hariThreshold = 7): void
    {
        $batasWaktu = Carbon::today()->subDays($hariThreshold);
        $today      = Carbon::today()->toDateString();

        $santriList = Santri::active()
            ->with(['halaqah.ustadz.user'])
            ->get();

        foreach ($santriList as $santri) {
            $adaSetoran = $santri->setoran()
                ->where('tanggal_setoran', '>=', $batasWaktu)
                ->exists();

            if ($adaSetoran) {
                continue;
            }

            $ustadz = $santri->halaqah?->ustadz;
            if (! $ustadz || ! $ustadz->user) {
                continue;
            }

            $userId = $ustadz->user->id;

            // Hindari duplikasi: cek notifikasi serupa hari ini
            $sudahAda = Notifikasi::where('user_id', $userId)
                ->where('tipe', 'tidak_setor')
                ->whereDate('created_at', $today)
                ->where('pesan', 'like', "%{$santri->nama_lengkap}%")
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Notifikasi::create([
                'user_id' => $userId,
                'judul'   => 'Santri Tidak Menyetor',
                'pesan'   => "Santri {$santri->nama_lengkap} belum menyetor hafalan dalam {$hariThreshold} hari terakhir.",
                'tipe'    => 'tidak_setor',
                'is_dibaca' => false,
            ]);
        }
    }

    /**
     * Cek target yang batas waktunya telah lewat dan belum tercapai,
     * lalu buat notifikasi untuk ustadz dan admin.
     */
    public function cekTargetTerlewat(): void
    {
        $today = Carbon::today();

        $targetList = Target::where('batas_waktu', '<', $today)
            ->where('is_tercapai', false)
            ->with(['santri', 'ustadz.user'])
            ->get();

        $adminUsers = User::where('role', 'admin')->where('is_active', true)->get();

        foreach ($targetList as $target) {
            $santriNama = $target->santri?->nama_lengkap ?? 'Santri';
            $pesan      = "Target hafalan {$target->target_juz} juz untuk santri {$santriNama} telah melewati batas waktu dan belum tercapai.";
            $todayStr   = $today->toDateString();

            // Notifikasi untuk ustadz
            if ($target->ustadz && $target->ustadz->user) {
                $ustadzUserId = $target->ustadz->user->id;

                $sudahAda = Notifikasi::where('user_id', $ustadzUserId)
                    ->where('tipe', 'target_terlewat')
                    ->whereDate('created_at', $todayStr)
                    ->where('pesan', $pesan)
                    ->exists();

                if (! $sudahAda) {
                    Notifikasi::create([
                        'user_id'   => $ustadzUserId,
                        'judul'     => 'Target Hafalan Terlewat',
                        'pesan'     => $pesan,
                        'tipe'      => 'target_terlewat',
                        'is_dibaca' => false,
                    ]);
                }
            }

            // Notifikasi untuk semua admin
            foreach ($adminUsers as $admin) {
                $sudahAda = Notifikasi::where('user_id', $admin->id)
                    ->where('tipe', 'target_terlewat')
                    ->whereDate('created_at', $todayStr)
                    ->where('pesan', $pesan)
                    ->exists();

                if (! $sudahAda) {
                    Notifikasi::create([
                        'user_id'   => $admin->id,
                        'judul'     => 'Target Hafalan Terlewat',
                        'pesan'     => $pesan,
                        'tipe'      => 'target_terlewat',
                        'is_dibaca' => false,
                    ]);
                }
            }
        }
    }
}
