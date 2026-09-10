<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('praktikum_nama', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('praktikum');
            $table->string('semester');
            $table->boolean('is_active')->default(false);
        });
        Schema::create('praktikum_jadwal', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('praktikum');
            $table->string('plug');
            $table->string('hari');
            $table->string('jam');
            $table->boolean('is_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('praktikum_nama');
        Schema::dropIfExists('praktikum_jadwal');
    }
};
