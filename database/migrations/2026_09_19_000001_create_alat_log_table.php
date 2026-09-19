<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alat', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::create('alat_log', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('id_log')->index();
            $table->string('keperluan');
            $table->integer('nim_pic')->nullable();
            $table->string('nama_pic')->nullable();
            $table->string('inventaris');
            $table->json('kondisi');
            $table->string('status')->comment('keluar|masuk');

            $table->foreign('inventaris')
                ->references('inventaris')
                ->on('alat')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alat_log');

        Schema::table('alat', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
