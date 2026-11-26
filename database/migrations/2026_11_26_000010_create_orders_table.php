<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique()->comment('contoh: ORD-20261126-001');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete()
                ->comment('null = pembeli umum / walk-in');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()
                ->comment('kasir yang melayani');
            $table->enum('channel', ['toko', 'online'])->default('toko');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('shipping_address')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])->default('pending');
            $table->enum('payment_status', ['belum_bayar', 'lunas', 'refund'])->default('belum_bayar');
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete();
            $table->string('product_name')->comment('snapshot nama saat transaksi');
            $table->unsignedInteger('quantity');
            $table->decimal('price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
