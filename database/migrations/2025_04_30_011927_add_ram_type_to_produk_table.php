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
        Schema::table('produk', function (Blueprint $table) {
            $table->string('tipe_ram')->nullable()->comment('DDR3, DDR4, DDR5');
            $table->integer('max_ram')->nullable()->comment('Dalam GB (untuk motherboard)');
            $table->integer('kecepatan')->nullable()->comment('Dalam MHz (untuk RAM)');
            $table->integer('daya')->nullable()->comment('Dalam watt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('tipe_ram');
            $table->dropColumn('max_ram');
            $table->dropColumn('kecepatan');
            $table->dropColumn('daya');
        });
    }
};
