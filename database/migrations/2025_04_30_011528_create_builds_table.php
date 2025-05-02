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
        Schema::create('builds', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique()->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique()->nullable();
            $table->text('description')->nullable();
            $table->decimal('total_price', 15, 2)->default(0);
            $table->enum('mode', ['compatibility', 'free'])->default('compatibility');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            // Index untuk optimasi query
            $table->index(['user_id', 'status', 'kode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};
