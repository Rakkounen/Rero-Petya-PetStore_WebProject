<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Daftar layanan grooming (mandi, potong kuku, dll) & add-on penitipan
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['grooming', 'penitipan_addon']);
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('pet_species', ['semua', 'anjing', 'kucing', 'lainnya'])->default('semua');
            $table->decimal('price', 12, 2);
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
