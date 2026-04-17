<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat id_petugas nullable agar absensi publik (oleh siswa sendiri) bisa dicatat tanpa id petugas.
     */
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Drop foreign key dulu sebelum ubah tipe kolom
            $table->dropForeign(['id_petugas']);
            // Ubah kolom jadi nullable
            $table->unsignedBigInteger('id_petugas')->nullable()->change();
            // Re-add foreign key constraint
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['id_petugas']);
            $table->unsignedBigInteger('id_petugas')->nullable(false)->change();
            $table->foreign('id_petugas')->references('id_petugas')->on('petugas')->onDelete('cascade');
        });
    }
};
