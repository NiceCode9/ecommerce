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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('pengguna_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pesanan_id')->constrained('pesanan')->onDelete('cascade');
            $table->tinyInteger('rating')->comment('Skala 1-5');
            $table->text('komentar')->nullable();
            $table->string('gambar')->nullable();
            $table->text('balasan_admin')->nullable();
            $table->timestamps();

            $table->unique(['pengguna_id', 'pesanan_id', 'produk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
