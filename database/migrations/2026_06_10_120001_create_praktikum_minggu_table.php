<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('praktikum_minggu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('praktikum_id')->constrained('praktikum')->cascadeOnDelete();
            $table->integer('minggu_ke');
            $table->string('topik')->nullable();
            $table->date('tanggal')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['praktikum_id', 'minggu_ke']);
            $table->index('praktikum_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('praktikum_minggu');
    }
};
