<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kandang / ruang penitipan
        Schema::create('boarding_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name');
            $table->enum('room_type', ['standar', 'deluxe', 'vip'])->default('standar');
            $table->enum('pet_species', ['anjing', 'kucing', 'kelinci', 'lainnya'])->default('kucing');
            $table->enum('size', ['kecil', 'sedang', 'besar'])->default('kecil');
            $table->decimal('price_per_day', 12, 2);
            $table->text('facilities')->nullable()->comment('AC, CCTV, kasur, dll');
            $table->enum('status', ['tersedia', 'terisi', 'perawatan'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_rooms');
    }
};
