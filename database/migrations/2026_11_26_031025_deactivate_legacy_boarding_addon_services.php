<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('services')
            ->where('type', 'penitipan_addon')
            ->where('name', 'Mandi Saat Penitipan')
            ->update(['is_active' => false]);
    }

    public function down(): void
    {
        DB::table('services')
            ->where('type', 'penitipan_addon')
            ->where('name', 'Mandi Saat Penitipan')
            ->update(['is_active' => true]);
    }
};
