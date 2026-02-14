<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('role', 20)->default('admin')->after('email');
        });

        // Pastikan akun admin@makam.com jadi superadmin jika ada
        DB::table('admins')->where('email', 'admin@makam.com')->update(['role' => 'superadmin']);
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};

