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
        Schema::create('detail_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pricings')->nullable()->constrained('pricings')->onDelete('cascade');
            $table->string('name');
            $table->text('deskripsi');
            $table->string('status');
            $table->text('keuntungan');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pricings');
    }
};
