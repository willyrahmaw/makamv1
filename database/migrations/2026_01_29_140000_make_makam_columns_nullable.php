<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            // Drop foreign key dulu agar blok_id bisa diubah jadi nullable
            Schema::table('makam', function ($table) {
                $table->dropForeign(['blok_id']);
            });

            DB::statement('ALTER TABLE makam MODIFY nama_lengkap VARCHAR(255) NULL');
            DB::statement("ALTER TABLE makam MODIFY jenis_kelamin ENUM('laki-laki','perempuan','tidak-diketahui') NULL");
            DB::statement('ALTER TABLE makam MODIFY tanggal_wafat DATE NULL');
            DB::statement('ALTER TABLE makam MODIFY blok_id BIGINT UNSIGNED NULL');

            Schema::table('makam', function ($table) {
                $table->foreign('blok_id')->references('id')->on('blok_makam')->onDelete('cascade');
            });
        }

        // SQLite / driver lain: gunakan DBAL atau jalankan migration hanya di MySQL
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'mysql') {
            Schema::table('makam', function ($table) {
                $table->dropForeign(['blok_id']);
            });

            DB::statement('ALTER TABLE makam MODIFY nama_lengkap VARCHAR(255) NOT NULL');
            DB::statement("ALTER TABLE makam MODIFY jenis_kelamin ENUM('laki-laki','perempuan','tidak-diketahui') NOT NULL");
            DB::statement('ALTER TABLE makam MODIFY tanggal_wafat DATE NOT NULL');
            DB::statement('ALTER TABLE makam MODIFY blok_id BIGINT UNSIGNED NOT NULL');

            Schema::table('makam', function ($table) {
                $table->foreign('blok_id')->references('id')->on('blok_makam')->onDelete('cascade');
            });
        }
    }
};
