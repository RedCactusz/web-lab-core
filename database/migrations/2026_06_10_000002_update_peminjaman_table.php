<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('keperluan')->nullable()->after('status');
            $table->text('alasan_lainnya')->nullable()->after('keperluan');
            $table->string('jam_pinjam')->nullable()->after('tanggal_pinjam');
            $table->string('jam_kembali')->nullable()->after('tanggal_kembali');
            $table->json('items')->nullable()->after('jam_kembali');
            $table->json('revised_items')->nullable()->after('items');
            $table->text('revisi_catatan')->nullable()->after('revised_items');
            $table->text('pengembalian_catatan')->nullable()->after('revisi_catatan');
            $table->json('pengembalian_items')->nullable()->after('pengembalian_catatan');
            $table->date('tanggal_dikembalikan')->nullable()->after('pengembalian_items');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn([
                'keperluan', 'alasan_lainnya', 'jam_pinjam', 'jam_kembali',
                'items', 'revised_items', 'revisi_catatan', 'pengembalian_catatan',
                'pengembalian_items', 'tanggal_dikembalikan',
            ]);
        });
    }
};
