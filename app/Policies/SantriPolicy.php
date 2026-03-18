<?php

namespace App\Policies;

use App\Models\Santri;
use App\Models\User;

class SantriPolicy
{
    /**
     * Admin dan ustadz bisa melihat daftar santri.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'ustadz']);
    }

    /**
     * Admin bisa lihat semua santri.
     * Ustadz hanya bisa lihat santri di halaqahnya.
     */
    public function view(User $user, Santri $santri): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'ustadz' && $user->ustadz) {
            $halaqahIds = $user->ustadz->halaqah()->pluck('id');
            return $halaqahIds->contains($santri->halaqah_id);
        }

        return false;
    }

    /**
     * Hanya admin yang bisa membuat santri baru.
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya admin yang bisa mengubah data santri.
     */
    public function update(User $user, Santri $santri): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Hanya admin yang bisa menonaktifkan santri.
     */
    public function delete(User $user, Santri $santri): bool
    {
        return $user->role === 'admin';
    }
}
