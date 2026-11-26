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
        Schema::table('boarding_rooms', function (Blueprint $table) {
            $table->string('pet_species', 20)->default('kucing')->change();
        });

        $catService = DB::table('boarding_rooms')->where('code', 'CAT-STD')->first();
        $dogService = DB::table('boarding_rooms')->where('code', 'DOG-DELUXE')->first();
        $serviceToKeep = $catService ?? $dogService;

        if ($serviceToKeep === null) {
            return;
        }

        if ($catService !== null && $dogService !== null) {
            DB::table('boardings')
                ->where('boarding_room_id', $dogService->id)
                ->update(['boarding_room_id' => $catService->id]);

            DB::table('boarding_rooms')->where('id', $dogService->id)->delete();
        }

        DB::table('boarding_rooms')
            ->where('id', $serviceToKeep->id)
            ->update([
                'code' => 'CAT-STD',
                'name' => 'Penitipan Anjing & Kucing',
                'room_type' => 'standar',
                'pet_species' => 'anjing_kucing',
                'size' => 'sedang',
                'price_per_day' => 75000,
                'facilities' => 'Layanan penitipan yang menerima anjing dan kucing dengan tarif yang sama.',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('boarding_rooms')
            ->where('pet_species', 'anjing_kucing')
            ->update(['pet_species' => 'kucing']);

        Schema::table('boarding_rooms', function (Blueprint $table) {
            $table->enum('pet_species', ['anjing', 'kucing', 'kelinci', 'lainnya'])
                ->default('kucing')
                ->change();
        });
    }
};
