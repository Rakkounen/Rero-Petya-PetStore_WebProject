<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
        });

        foreach (DB::table('users')->select('id', 'email')->orderBy('id')->cursor() as $user) {
            $username = Str::lower(Str::before($user->email, '@'));
            $username = preg_replace('/[^a-z0-9_-]/', '', $username) ?: 'user';
            $username = substr($username, 0, 240);
            $candidate = $username;
            $suffix = 1;

            while (DB::table('users')->where('username', $candidate)->exists()) {
                $candidate = substr($username, 0, 240 - strlen((string) $suffix)).'-'.$suffix;
                $suffix++;
            }

            DB::table('users')->where('id', $user->id)->update(['username' => $candidate]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->enum('role', ['admin', 'kasir', 'groomer', 'staff_penitipan', 'pelanggan'])
                ->default('pelanggan')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->where('role', 'pelanggan')->update(['role' => 'kasir']);

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'kasir', 'groomer', 'staff_penitipan'])
                ->default('kasir')
                ->change();
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
