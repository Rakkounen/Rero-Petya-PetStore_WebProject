<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_category_id')->constrained()->restrictOnDelete();
            $table->string('sku', 50)->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('brand')->nullable();
            $table->enum('pet_species', ['semua', 'anjing', 'kucing', 'kelinci', 'burung', 'hamster', 'lainnya'])->default('semua');
            $table->text('description')->nullable();
            $table->decimal('weight', 8, 2)->nullable()->comment('berat bersih dalam kg');
            $table->decimal('cost_price', 12, 2)->default(0)->comment('harga modal');
            $table->decimal('price', 12, 2)->comment('harga jual');
            $table->integer('stock')->default(0);
            $table->unsignedInteger('min_stock')->default(5);
            $table->date('expired_date')->nullable()->comment('untuk makanan');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('pet_species');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
