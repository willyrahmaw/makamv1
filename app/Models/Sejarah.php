<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sejarah extends Model
{
    protected $table = 'sejarah';

    protected $fillable = [
        'konten',
        'narasumber_nama',
        'narasumber_lahir',
        'narasumber_jabatan',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public static function getAktif()
    {
        return static::where('aktif', true)->first();
    }
}
