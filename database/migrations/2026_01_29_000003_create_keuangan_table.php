<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keuangan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('tipe', ['pemasukan', 'pengeluaran']);
            $table->string('sumber')->nullable();      // contoh: Donasi Jamaah, Operasional, dll.
            $table->string('metode')->nullable();      // contoh: Tunai, Transfer, QRIS
            $table->string('referensi')->nullable();   // contoh: No. bukti / No. transaksi
            $table->string('donatur')->nullable();     // khusus jika tipe donasi
            $table->text('deskripsi')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keuangan');
    }
};

