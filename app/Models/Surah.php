<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    protected $table = 'surah';

    public $timestamps = false;

    protected $fillable = [
        'nomor_surah',
        'nama_surah',
        'nama_latin',
        'jumlah_ayat',
        'jenis',
    ];

    public function setoran(): HasMany
    {
        return $this->hasMany(Setoran::class, 'surah_id');
    }
}
