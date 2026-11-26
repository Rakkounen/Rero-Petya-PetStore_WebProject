<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir', 'groomer', 'staff_penitipan'])
                ->default('kasir')
                ->change();
        });

        DB::table('users')
            ->where('email', 'test@example.com')
            ->where('name', 'Test User')
            ->update(['role' => 'kasir']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir', 'groomer', 'staff_penitipan'])
                ->default('kasir')
                ->change();
        });
    }
};
