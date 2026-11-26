<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pembayaran untuk Boarding, Grooming, maupun Order (polymorphic)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->unique();
            $table->morphs('payable'); // payable_type + payable_id
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['tunai', 'transfer', 'qris', 'kartu_debit', 'kartu_kredit', 'ewallet']);
            $table->enum('type', ['dp', 'pelunasan', 'penuh'])->default('penuh');
            $table->enum('status', ['pending', 'berhasil', 'gagal', 'refund'])->default('berhasil');
            $table->string('reference_number')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
