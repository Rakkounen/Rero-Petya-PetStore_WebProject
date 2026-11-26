<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('species', ['anjing', 'kucing', 'kelinci', 'burung', 'hamster', 'reptil', 'lainnya']);
            $table->string('breed')->nullable();
            $table->enum('gender', ['jantan', 'betina'])->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('weight', 6, 2)->nullable()->comment('kg');
            $table->enum('size', ['kecil', 'sedang', 'besar'])->default('kecil');
            $table->string('color')->nullable();
            $table->boolean('is_vaccinated')->default(false);
            $table->date('last_vaccination_date')->nullable();
            $table->text('medical_notes')->nullable()->comment('alergi, penyakit, obat rutin');
            $table->text('behavior_notes')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('species');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
