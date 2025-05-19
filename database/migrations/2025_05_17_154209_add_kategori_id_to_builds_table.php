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
        Schema::table('builds', function (Blueprint $table) {
            $table->foreignId('kategori_id')->after('user_id')->nullable()->constrained('kategori_builds')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table) {
            //
        });
    }
};
