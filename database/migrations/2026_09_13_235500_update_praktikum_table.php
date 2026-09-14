<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('praktikum_nama', function (Blueprint $table) {
            $table->renameColumn('praktikum', 'praktikum_label');
            $table->string('praktikum_slug')->nullable()->after('praktikum_label');
        });

        Schema::table('praktikum_jadwal', function (Blueprint $table) {
            $table->renameColumn('praktikum', 'praktikum_label');
            $table->string('praktikum_slug')->nullable()->after('praktikum_label');
        });
    }

    public function down()
    {
        Schema::table('praktikum_nama', function (Blueprint $table) {
            $table->renameColumn('praktikum_label', 'praktikum');
            $table->dropColumn('praktikum_slug');
        });

        Schema::table('praktikum_jadwal', function (Blueprint $table) {
            $table->renameColumn('praktikum_label', 'praktikum');
            $table->dropColumn('praktikum_slug');
        });
    }

};