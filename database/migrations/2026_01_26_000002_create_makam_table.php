<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makam', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('nama_ayah')->nullable(); // Untuk bin/binti
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_wafat');
            $table->integer('usia')->nullable(); // Dihitung otomatis
            $table->foreignId('blok_id')->constrained('blok_makam')->onDelete('cascade');
            $table->string('nomor_makam')->nullable(); // Nomor urut dalam blok
            $table->string('ahli_waris')->nullable();
            $table->string('telepon_ahli_waris')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makam');
    }
};
