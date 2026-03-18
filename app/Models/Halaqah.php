<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Halaqah extends Model
{
    protected $table = 'halaqah';

    protected $fillable = [
        'nama',
        'ustadz_id',
    ];

    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function santri(): HasMany
    {
        return $this->hasMany(Santri::class);
    }
}
