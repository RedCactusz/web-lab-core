<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_parameter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('praktikum_minggu_id')->constrained('praktikum_minggu')->cascadeOnDelete();
            $table->string('nama');
            $table->decimal('bobot', 5, 3);
            $table->string('tipe')->default('numeric');
            $table->integer('max_nilai')->default(100);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->unique(['praktikum_minggu_id', 'nama']);
            $table->index('praktikum_minggu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_parameter');
    }
};
