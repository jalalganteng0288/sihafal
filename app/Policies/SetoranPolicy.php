<?php

namespace App\Policies;

use App\Models\Santri;
use App\Models\Setoran;
use App\Models\User;

class SetoranPolicy
{
    /**
     * Admin dan ustadz bisa melihat daftar setoran.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'ustadz']);
    }

    /**
     * Admin bisa lihat semua setoran.
     * Ustadz hanya bisa lihat setoran santri di halaqahnya.
     * Santri hanya bisa lihat setoran miliknya sendiri.
     */
    public function view(User $user, Setoran $setoran): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'ustadz' && $user->ustadz) {
            $halaqahIds = $user->ustadz->halaqah()->pluck('id');
            $santriIds  = Santri::whereIn('halaqah_id', $halaqahIds)->pluck('id');
            return $santriIds->contains($setoran->santri_id);
        }

        if ($user->role === 'santri' && $user->santri) {
            return $setoran->santri_id === $user->santri->id;
        }

        return false;
    }

    /**
     * Hanya ustadz yang bisa mencatat setoran baru.
     */
    public function create(User $user): bool
    {
        return $user->role === 'ustadz';
    }

    /**
     * Ustadz yang mencatat setoran tersebut yang bisa mengubahnya.
     */
    public function update(User $user, Setoran $setoran): bool
    {
        if ($user->role !== 'ustadz' || ! $user->ustadz) {
            return false;
        }

        return $setoran->ustadz_id === $user->ustadz->id;
    }
}
