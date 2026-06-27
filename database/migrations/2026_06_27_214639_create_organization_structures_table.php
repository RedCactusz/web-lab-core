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
        Schema::create('organization_structures', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['kepala_lab', 'dosen_lab', 'praktikum_section', 'praktikum_pengajar']);
            $table->string('name')->nullable()->comment('Nama person untuk type kepala_lab/dosen_lab/praktikum_pengajar');
            $table->string('role')->nullable()->comment('Role/jabatan untuk type kepala_lab/dosen_lab');
            $table->string('image')->nullable();
            $table->string('section_name')->nullable()->comment('Nama praktikum/section untuk type praktikum_section');
            $table->foreignId('parent_id')->nullable()->constrained('organization_structures')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['type', 'is_published']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_structures');
    }
};
