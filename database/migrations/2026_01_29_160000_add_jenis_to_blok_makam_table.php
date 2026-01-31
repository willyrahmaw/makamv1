<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blok_makam', function (Blueprint $table) {
            $table->string('jenis', 20)->default('umum')->after('nama_blok');
        });

        // Pastikan blok yang ada dapat nilai jenis
        DB::table('blok_makam')->whereNull('jenis')->update(['jenis' => 'umum']);

        // Tambah Blok Pendopo (makam khusus) jika belum ada
        if (DB::table('blok_makam')->where('nama_blok', 'Blok Pendopo')->doesntExist()) {
            DB::table('blok_makam')->insert([
                'nama_blok' => 'Blok Pendopo',
                'jenis' => 'khusus',
                'keterangan' => 'Makam khusus (Pendopo)',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('blok_makam')->where('nama_blok', 'Blok Pendopo')->delete();
        Schema::table('blok_makam', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
