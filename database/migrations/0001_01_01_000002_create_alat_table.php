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
            $table->softDeletes();
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

        Schema::create('alat_log', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('id_log')->index();
            $table->string('keperluan')->comment('terjemahan kode event, "-" untuk event inventaris');
            $table->integer('nim_pic')->nullable();
            $table->string('nama_pic')->nullable();
            $table->string('inventaris');
            $table->json('kondisi');
            $table->string('status')->comment('pengajuan|keluar|masuk|tambah|hapus|edit');

            $table->foreign('inventaris')
                ->references('inventaris')
                ->on('alat')
                ->restrictOnDelete();
        });

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('nim')->index();
            $table->string('nama');
            $table->string('keperluan')->comment('praktikum|belajar_mandiri|penelitian|penyewaan_komersil');
            $table->string('praktikum_slug')->nullable()->comment('wajib terisi ketika keperluan = praktikum');
            $table->string('status')->default('pending')->comment('pending|disetujui|ditolak');
            $table->string('catatan')->nullable();
            $table->string('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('returned_at')->nullable();
        });

        Schema::create('peminjaman_alat', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alat')->restrictOnDelete();
            $table->string('inventaris');
            $table->integer('jumlah');

            $table->unique(['peminjaman_id', 'alat_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat');
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('alat_log');
        Schema::dropIfExists('alat');
    }
};
