<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontakAdmin extends Model
{
    protected $table = 'kontak_admin';

    protected $fillable = [
        'telepon',
        'email',
        'alamat',
        'jam_layanan',
    ];

    /**
     * Get the first (and only) contact record
     */
    public static function getKontak()
    {
        return static::first() ?? static::create([
            'telepon' => '+62 812-3456-7890',
            'email' => 'admin@makam-ngadirejo.com',
            'alamat' => "Kantor Desa Ngadirejo\nJl. Raya Ngadirejo, Kecamatan Ngadirejo\nKabupaten Temanggung, Jawa Tengah",
            'jam_layanan' => "Senin - Jumat: 08:00 - 16:00 WIB\nSabtu: 08:00 - 12:00 WIB",
        ]);
    }
}
