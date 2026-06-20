<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajar', function (Blueprint $table) {
            $table->foreign('praktikum_slug')->references('slug')->on('praktikum')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pengajar', function (Blueprint $table) {
            $table->dropForeign(['praktikum_slug']);
        });
    }
};
