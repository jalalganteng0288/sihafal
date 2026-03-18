<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\Setoran;

class SetoranObserver
{
    /**
     * Catat log saat setoran baru dibuat.
     */
    public function created(Setoran $setoran): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'create_setoran',
            'model_type' => Setoran::class,
            'model_id'   => $setoran->id,
            'data_lama'  => null,
            'data_baru'  => $setoran->toArray(),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Catat log saat setoran diubah.
     */
    public function updated(Setoran $setoran): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'update_setoran',
            'model_type' => Setoran::class,
            'model_id'   => $setoran->id,
            'data_lama'  => $setoran->getOriginal(),
            'data_baru'  => $setoran->toArray(),
            'ip_address' => request()->ip(),
        ]);
    }

    /**
     * Catat log saat setoran dihapus.
     */
    public function deleted(Setoran $setoran): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'aksi'       => 'delete_setoran',
            'model_type' => Setoran::class,
            'model_id'   => $setoran->id,
            'data_lama'  => $setoran->toArray(),
            'data_baru'  => null,
            'ip_address' => request()->ip(),
        ]);
    }
}
