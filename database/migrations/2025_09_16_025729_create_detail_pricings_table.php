<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pricings')->nullable()->constrained('pricings')->onDelete('cascade');
            $table->json('name')->nullable();     // ubah jadi json
            $table->text('deskripsi');
            $table->text('deskripsi2');
            $table->json('status')->nullable();   // ubah jadi json
            $table->text('keuntungan');
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pricings');
    }
};
