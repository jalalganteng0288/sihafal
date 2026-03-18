<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Santri extends Model
{
    protected $table = 'santri';

    protected $fillable = [
        'user_id',
        'nomor_induk',
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'halaqah_id',
        'tanggal_masuk',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
            'tanggal_masuk' => 'date',
            'is_active'     => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function halaqah(): BelongsTo
    {
        return $this->belongsTo(Halaqah::class);
    }

    public function setoran(): HasMany
    {
        return $this->hasMany(Setoran::class);
    }

    public function target(): HasMany
    {
        return $this->hasMany(Target::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getTotalHafalanAyatAttribute(): int
    {
        return $this->setoran()
            ->where('jenis', 'setoran_baru')
            ->sum('jumlah_ayat_disetor');
    }
}
