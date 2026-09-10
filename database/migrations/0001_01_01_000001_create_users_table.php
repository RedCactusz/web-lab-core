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
        Schema::create('user_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->integer('nim')->unique();
            $table->string('surel')->unique();
            $table->integer('angkatan');
            $table->boolean('is_asisten')->default(false);
            $table->string('pengampu')->nullable();
            $table->string('pengampu_plug')->nullable();
            $table->boolean('is_active_asisten')->default(false);
            $table->string('password');
            $table->boolean('is_active_user')->default(false);
        });
        Schema::create('user_dosen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nama');
            $table->integer('nip')->unique();
            $table->string('surel')->unique();
            $table->string('pengampu');
            $table->string('password');
            $table->boolean('is_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_mahasiswa');
        Schema::dropIfExists('user_dosen');
    }
};
