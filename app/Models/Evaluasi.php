<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluasi extends Model
{
    protected $table = 'evaluasi';

    protected $fillable = [
        'setoran_id',
        'nilai_kelancaran',
        'nilai_tajwid',
        'nilai_makhraj',
        'nilai_akhir',
        'kategori',
        'catatan_evaluasi',
    ];

    protected function casts(): array
    {
        return [
            'nilai_kelancaran' => 'decimal:2',
            'nilai_tajwid'     => 'decimal:2',
            'nilai_makhraj'    => 'decimal:2',
            'nilai_akhir'      => 'decimal:2',
        ];
    }

    public function setoran(): BelongsTo
    {
        return $this->belongsTo(Setoran::class);
    }
}
