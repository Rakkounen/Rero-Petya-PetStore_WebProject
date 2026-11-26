<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('pet_species', ['semua', 'anjing', 'kucing', 'reptil', 'lainnya'])
                ->default('semua')
                ->change();
        });

        DB::table('services')
            ->where('type', 'grooming')
            ->where('name', 'Grooming Dasar')
            ->update([
                'name' => 'Grooming Reptil',
                'description' => 'Perawatan khusus yang disesuaikan untuk hewan reptil.',
                'pet_species' => 'reptil',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->enum('pet_species', ['semua', 'anjing', 'kucing', 'lainnya'])
                ->default('semua')
                ->change();
        });

        DB::table('services')
            ->where('type', 'grooming')
            ->where('name', 'Grooming Reptil')
            ->update([
                'name' => 'Grooming Dasar',
                'description' => 'Mandi, pengeringan, dan menyisir bulu.',
                'pet_species' => 'semua',
            ]);
    }
};
