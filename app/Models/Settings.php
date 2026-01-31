<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as array
     */
    public static function getAll()
    {
        return static::pluck('value', 'key')->toArray();
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaults()
    {
        $defaults = [
            'site_name' => 'Digitalisasi Makam',
            'site_description' => 'Sistem digitalisasi makam untuk memudahkan pencarian dan pengelolaan data makam',
            'site_logo' => null,
            'footer_text' => '© ' . date('Y') . ' Digitalisasi Makam. Semua hak dilindungi.',
            'meta_description' => 'Sistem digitalisasi makam untuk memudahkan pencarian dan pengelolaan data makam',
            'meta_keywords' => 'makam, digitalisasi, ngadirejo, temanggung',
            // Warna blok berdasarkan status
            'blok_warna_merah' => '#FF6B6B', // Penuh
            'blok_warna_kuning' => '#FFD93D', // Lumayan
            'blok_warna_hijau' => '#6BCF7F', // Ada
            'blok_warna_putih' => '#FFFFFF', // Default/Kosong
            // Threshold jumlah makam untuk menentukan warna
            'blok_threshold_merah' => '10', // >= 10 makam = merah (penuh)
            'blok_threshold_kuning' => '5', // >= 5 makam = kuning (lumayan)
            'map_embed_url' => '', // URL src untuk iframe Google Maps
            'layanan_kelurahan' => "Surat Keterangan Domisili\nKartu Keluarga (KK)\nKTP-El\nSurat Keterangan Tidak Mampu (SKTM)\nSurat Keterangan Usaha\nPengantar Nikah\nLainnya (sesuai kebutuhan)",
        ];

        foreach ($defaults as $key => $value) {
            // Only set if the key doesn't exist
            if (!static::where('key', $key)->exists()) {
                static::set($key, $value);
            }
        }
    }
}
