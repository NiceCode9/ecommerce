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
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 100)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->enum('tipe', ['processor', 'motherboard', 'memory', 'general'])->default('general');
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('kategori')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
