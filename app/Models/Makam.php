<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Makam extends Model
{
    protected $table = 'makam';

    protected $fillable = [
        'nama_lengkap',
        'dikenali',
        'jenis_kelamin',
        'nama_ayah',
        'tanggal_lahir',
        'tanggal_wafat',
        'usia',
        'blok_id',
        'nomor_makam',
        'catatan',
        'ahli_waris',
        'telepon_ahli_waris',
        'keterangan',
        'foto',
        'cover',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_wafat' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($makam) {
            // Hitung usia otomatis jika tanggal lahir tersedia
            if ($makam->tanggal_lahir && $makam->tanggal_wafat) {
                $tanggalLahir = Carbon::parse($makam->tanggal_lahir);
                $tanggalWafat = Carbon::parse($makam->tanggal_wafat);
                
                // Hitung usia dengan lebih akurat
                $makam->usia = $tanggalLahir->diffInYears($tanggalWafat);
                
                // Jika belum genap setahun, set ke 0
                if ($makam->usia < 0) {
                    $makam->usia = 0;
                }
            } else {
                // Jika tidak ada tanggal lahir, set usia null
                $makam->usia = null;
            }
        });
    }
    
    // Accessor untuk mendapatkan usia (jika belum dihitung)
    public function getUsiaAttribute($value)
    {
        // Jika usia sudah ada di database dan valid, return
        if ($value !== null && $value >= 0) {
            return (int) $value;
        }
        
        // Jika belum ada tapi ada tanggal lahir dan wafat, hitung sekarang
        if ($this->attributes['tanggal_lahir'] && $this->attributes['tanggal_wafat']) {
            try {
                $tanggalLahir = Carbon::parse($this->attributes['tanggal_lahir']);
                $tanggalWafat = Carbon::parse($this->attributes['tanggal_wafat']);
                
                if ($tanggalWafat->gte($tanggalLahir)) {
                    $usia = $tanggalLahir->diffInYears($tanggalWafat);
                    return $usia >= 0 ? (int) $usia : null;
                }
            } catch (\Exception $e) {
                return null;
            }
        }
        
        return null;
    }

    public function blok(): BelongsTo
    {
        return $this->belongsTo(BlokMakam::class, 'blok_id');
    }

    // Accessor untuk nama lengkap dengan bin/binti
    public function getNamaLengkapBinBintiAttribute(): string
    {
        // Jika ditandai tidak dikenali, selalu tampilkan "Tidak diketahui"
        if ($this->attributes['dikenali'] ?? true ? false : true) {
            return 'Tidak diketahui';
        }

        $nama = $this->nama_lengkap ?: 'Tidak diketahui';

        // Jika jenis kelamin tidak diketahui, jangan tampilkan bin/binti
        if ($this->jenis_kelamin === 'tidak-diketahui') {
            return $nama;
        }

        if (empty($this->nama_ayah)) {
            return $nama;
        }

        $gelar = $this->jenis_kelamin === 'laki-laki' ? 'bin' : 'binti';
        return "{$nama} {$gelar} {$this->nama_ayah}";
    }

    // Accessor untuk format lokasi makam
    public function getLokasiMakamAttribute(): string
    {
        $lokasi = $this->blok?->nama_blok ?? 'Tidak diketahui';
        if ($this->nomor_makam) {
            $lokasi .= " - No. {$this->nomor_makam}";
        }
        return $lokasi;
    }
}
