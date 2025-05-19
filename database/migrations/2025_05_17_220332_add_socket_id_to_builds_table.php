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
            $table->foreignId('brand_id')->after('is_public')->nullable()->constrained('brands')->onDelete('set null');
            $table->foreignId('socket_id')->after('brand_id')->nullable()->constrained('sockets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builds', function (Blueprint $table) {
            $table->dropColumn('brand_id');
            $table->dropColumn('socket_id');
        });
    }
};
