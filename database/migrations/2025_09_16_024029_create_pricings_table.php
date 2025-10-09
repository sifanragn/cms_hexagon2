<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('pricings', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('title');
            $table->text('deskripsi')->nullable();
            $table->text('deskripsi2')->nullable();
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('diskon', 5, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricings');
    }
};
