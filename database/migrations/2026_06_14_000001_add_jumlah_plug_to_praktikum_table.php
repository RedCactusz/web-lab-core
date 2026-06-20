<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('praktikum', function (Blueprint $table) {
            $table->integer('jumlah_plug')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('praktikum', function (Blueprint $table) {
            $table->dropColumn('jumlah_plug');
        });
    }
};
