<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    protected $table = 'target';

    protected $fillable = [
        'santri_id',
        'ustadz_id',
        'target_juz',
        'batas_waktu',
        'catatan',
        'persentase_pencapaian',
        'is_tercapai',
    ];

    protected function casts(): array
    {
        return [
            'batas_waktu'           => 'date',
            'persentase_pencapaian' => 'decimal:2',
            'is_tercapai'           => 'boolean',
        ];
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class);
    }
}
