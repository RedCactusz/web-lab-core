<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alat')->unique();
            $table->string('nama');
            $table->enum('kategori', ['surveying', 'aksesoris', 'perlengkapan', 'lainnya'])->default('lainnya');
            $table->string('merk')->nullable();
            $table->string('tipe')->nullable();
            $table->enum('kondisi', ['baik', 'rusak_ringan', 'rusak_berat', 'maintenance'])->default('baik');
            $table->integer('jumlah')->default(0);
            $table->string('lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
