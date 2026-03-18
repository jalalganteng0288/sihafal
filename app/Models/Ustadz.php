<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ustadz extends Model
{
    protected $table = 'ustadz';

    protected $fillable = [
        'user_id',
        'nomor_identitas',
        'nama_lengkap',
        'spesialisasi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function halaqah(): HasMany
    {
        return $this->hasMany(Halaqah::class);
    }

    public function setoran(): HasMany
    {
        return $this->hasMany(Setoran::class);
    }

    public function target(): HasMany
    {
        return $this->hasMany(Target::class);
    }
}
