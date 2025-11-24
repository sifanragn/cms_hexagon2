<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('about_client', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('foto_client')->nullable();
            $table->tinyInteger('status')->default(1); 
            // 1 = Our Client, 0 = Media, 2 = Mitra, 3 = SMK Binaan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_client');
    }
};
