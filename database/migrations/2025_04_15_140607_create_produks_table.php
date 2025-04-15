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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('sku', 50)->unique()->nullable(); // stock keeping unit
            $table->longText('deskripsi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->decimal('diskon', 5, 2)->default(0.00);
            $table->integer('stok')->default(0);
            $table->decimal('berat', 10, 2)->nullable()->comment('dalam gram');
            $table->enum('kondisi', ['Baru', 'Bekas'])->default('Baru');
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brand')->onDelete('set null');
            $table->foreignId('socket_id')->nullable()->constrained('sockets')->onDelete('set null');
            // $table->foreignId('parent_id')->nullable()->constrained('produk')->onDelete('set null');
            $table->integer('garansi_bulan')->default(0);
            $table->boolean('is_aktif')->default(true);
            $table->integer('dilihat')->default(0);
            $table->timestamps();

            $table->foreign('mobo_id')->references('id')->on('produk')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
