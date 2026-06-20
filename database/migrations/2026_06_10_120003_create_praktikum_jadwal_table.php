<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('praktikum_jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('praktikum_id')->constrained('praktikum')->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('topik')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('praktikum_id');
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('praktikum_jadwal');
    }
};
