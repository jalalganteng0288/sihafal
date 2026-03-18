<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Santri;

class SantriObserver
{
    /**
     * Catat log saat santri baru dibuat.
     */
    public function created(Santri $santri): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'create_santri',
            'model_type' => Santri::class,
            'model_id'   => $santri->id,
            'data_lama'  => null,
            'data_baru'  => $santri->toArray(),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Catat log saat data santri diubah.
     */
    public function updated(Santri $santri): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'update_santri',
            'model_type' => Santri::class,
            'model_id'   => $santri->id,
            'data_lama'  => $santri->getOriginal(),
            'data_baru'  => $santri->toArray(),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Catat log saat santri dihapus/dinonaktifkan.
     */
    public function deleted(Santri $santri): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'delete_santri',
            'model_type' => Santri::class,
            'model_id'   => $santri->id,
            'data_lama'  => $santri->toArray(),
            'data_baru'  => null,
            'ip_address' => request()->ip(),
        ]);
    }
}
