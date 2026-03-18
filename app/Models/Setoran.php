<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Setoran extends Model
{
    protected $table = 'setoran';

    protected $fillable = [
        'santri_id',
        'ustadz_id',
        'tanggal_setoran',
        'jenis',
        'surah_id',
        'ayat_awal',
        'ayat_akhir',
        'jumlah_ayat_disetor',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_setoran' => 'date',
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

    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class, 'surah_id');
    }

    public function evaluasi(): HasOne
    {
        return $this->hasOne(Evaluasi::class);
    }
}
