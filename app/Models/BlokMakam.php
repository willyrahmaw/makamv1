<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlokMakam extends Model
{
    protected $table = 'blok_makam';

    protected $fillable = [
        'nama_blok',
        'keterangan',
    ];

    public function makam(): HasMany
    {
        return $this->hasMany(Makam::class, 'blok_id');
    }

    public function jumlahTerisi(): int
    {
        return $this->makam()->count();
    }
}
