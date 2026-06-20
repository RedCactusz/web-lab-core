<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_praktikum', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->cascadeOnDelete();
            $table->foreignId('praktikum_id')->constrained('praktikum')->cascadeOnDelete();
            $table->integer('kelompok')->nullable();
            $table->integer('plug')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'praktikum_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswa_praktikum');
    }
};
