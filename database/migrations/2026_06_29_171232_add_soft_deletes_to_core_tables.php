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
        // Add soft deletes to core tables
        $tables = [
            'users',
            'mahasiswa',
            'pengajar',
            'praktikum',
            'peminjaman',
            'inventaris',
            'agenda_items',
            'gallery_items',
            'news_items',
            'partners',
            'kerjasama',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->softDeletes();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'mahasiswa',
            'pengajar',
            'praktikum',
            'peminjaman',
            'inventaris',
            'agenda_items',
            'gallery_items',
            'news_items',
            'partners',
            'kerjasama',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }
        }
    }
};
