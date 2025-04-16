<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alamat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_penerima');
            $table->string('nomor_telepon', 15);
            $table->text('alamat_lengkap');
            $table->string('provinsi', 100);
            $table->string('kota', 100);
            $table->string('kecamatan', 100);
            $table->string('kelurahan', 100);
            $table->string('kode_pos', 10);
            $table->text('catatan')->nullable();
            $table->boolean('is_utama')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamat');
    }
};
