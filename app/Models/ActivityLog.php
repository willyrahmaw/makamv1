<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'admin_id',
        'event',
        'model_type',
        'model_id',
        'route',
        'method',
        'ip',
        'user_agent',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    /**
     * Label ramah untuk ditampilkan (bahasa Indonesia).
     */
    public static function eventLabels(): array
    {
        return [
            'makam_create' => 'Tambah data makam',
            'makam_update' => 'Ubah data makam',
            'makam_delete' => 'Hapus data makam',
            'blok_create' => 'Tambah blok makam',
            'blok_update' => 'Ubah blok makam',
            'blok_delete' => 'Hapus blok makam',
            'keuangan_create' => 'Tambah transaksi keuangan',
            'keuangan_update' => 'Ubah transaksi keuangan',
            'keuangan_delete' => 'Hapus transaksi keuangan',
            'sejarah_create' => 'Tambah sejarah',
            'sejarah_update' => 'Ubah sejarah',
            'sejarah_delete' => 'Hapus sejarah',
            'settings_update' => 'Ubah pengaturan website',
            'kontak_update' => 'Ubah kontak admin',
            'admin_user_create' => 'Tambah admin',
            'admin_user_update' => 'Ubah data admin',
            'admin_user_delete' => 'Hapus admin',
            'admin_login_success' => 'Login berhasil',
            'admin_login_failed' => 'Login gagal',
            'admin_login_rate_limited' => 'Login dibatasi (terlalu banyak percobaan)',
            'admin_logout' => 'Logout',
            'admin_password_changed' => 'Ganti password',
        ];
    }

    public function getEventLabelAttribute(): string
    {
        $labels = self::eventLabels();
        return $labels[$this->event] ?? $this->event;
    }
}

