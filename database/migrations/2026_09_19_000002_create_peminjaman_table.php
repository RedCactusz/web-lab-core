<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('nim')->index();
            $table->string('nama');
            $table->text('keperluan');
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

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat');
        Schema::dropIfExists('peminjaman');
    }
};
