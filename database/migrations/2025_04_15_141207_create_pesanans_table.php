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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pesanan', 50)->unique();
            $table->foreignId('pengguna_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['menunggu_pembayaran', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('menunggu_pembayaran');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('total_diskon', 15, 2)->default(0.00);
            $table->decimal('biaya_pengiriman', 10, 2)->default(0.00);
            $table->decimal('total_bayar', 15, 2);
            $table->foreignId('alamat_pengiriman_id')->constrained('alamat');
            $table->foreignId('metode_pengiriman_id')->constrained('metode_pengiriman');
            $table->string('nomor_resi', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
