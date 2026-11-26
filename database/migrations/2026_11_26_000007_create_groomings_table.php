<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groomings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->comment('contoh: GRM-20261126-001');
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('pet_id')->constrained()->restrictOnDelete();
            $table->foreignId('groomer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('boarding_id')->nullable()->constrained()->nullOnDelete()
                ->comment('diisi bila grooming bagian dari penitipan');
            $table->dateTime('scheduled_at');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['dijadwalkan', 'proses', 'selesai', 'dibatalkan'])->default('dijadwalkan');
            $table->enum('payment_status', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->timestamps();

            $table->index('scheduled_at');
            $table->index('status');
        });

        // Satu sesi grooming bisa berisi beberapa layanan
        Schema::create('grooming_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grooming_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grooming_service');
        Schema::dropIfExists('groomings');
    }
};
