<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boardings', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique()->comment('contoh: BRD-20261126-001');
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->foreignId('pet_id')->constrained()->restrictOnDelete();
            $table->foreignId('boarding_room_id')->constrained()->restrictOnDelete();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->dateTime('actual_check_in_at')->nullable();
            $table->dateTime('actual_check_out_at')->nullable();
            $table->unsignedSmallInteger('total_days');
            $table->decimal('price_per_day', 12, 2);
            $table->decimal('addon_total', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->text('feeding_instructions')->nullable();
            $table->text('special_notes')->nullable();
            $table->string('belongings')->nullable()->comment('barang bawaan: mainan, selimut, dll');
            $table->enum('status', ['dipesan', 'check_in', 'check_out', 'dibatalkan'])->default('dipesan');
            $table->enum('payment_status', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->timestamps();

            $table->index(['check_in_date', 'check_out_date']);
            $table->index('status');
        });

        // Add-on layanan pada penitipan (mis. mandi saat menginap)
        Schema::create('boarding_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boarding_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->restrictOnDelete();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boarding_service');
        Schema::dropIfExists('boardings');
    }
};
