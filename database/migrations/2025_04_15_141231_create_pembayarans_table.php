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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->unique()->constrained('pesanan')->onDelete('cascade');
            $table->string('kode_pembayaran', 100)->unique();
            $table->string('metode', 100);
            $table->string('gateway_id', 255)->nullable()->comment('ID transaksi dari payment gateway');
            $table->decimal('jumlah', 15, 2);
            $table->enum('status', ['pending', 'sukses', 'gagal', 'expired', 'refund'])->default('pending');
            $table->timestamp('waktu_dibayar')->nullable();
            $table->string('url_checkout')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
