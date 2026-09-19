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
        Schema::create('alat', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('inventaris')->unique();
            $table->string('nama_alat');
            $table->string('merk');
            $table->string('tipe');
            $table->string('serial_number');
            $table->integer('jumlah');
            $table->json('kondisi');
            $table->string('lokasi_penyimpanan');
            $table->string('ketersediaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
