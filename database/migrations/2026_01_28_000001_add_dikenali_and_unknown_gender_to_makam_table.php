<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('makam', function (Blueprint $table) {
            if (!Schema::hasColumn('makam', 'dikenali')) {
                $table->boolean('dikenali')->default(true)->after('id');
            }
        });

        // Tambahkan opsi "tidak-diketahui" ke enum jenis_kelamin (MySQL).
        // Jika bukan MySQL, statement ini aman di-skip via try/catch.
        try {
            DB::statement("ALTER TABLE makam MODIFY jenis_kelamin ENUM('laki-laki','perempuan','tidak-diketahui') NOT NULL");
        } catch (\Throwable $e) {
            // no-op
        }
    }

    public function down(): void
    {
        // Kembalikan enum ke kondisi sebelumnya (tanpa 'tidak-diketahui') jika memungkinkan
        try {
            DB::statement("ALTER TABLE makam MODIFY jenis_kelamin ENUM('laki-laki','perempuan') NOT NULL");
        } catch (\Throwable $e) {
            // no-op
        }

        Schema::table('makam', function (Blueprint $table) {
            if (Schema::hasColumn('makam', 'dikenali')) {
                $table->dropColumn('dikenali');
            }
        });
    }
};

