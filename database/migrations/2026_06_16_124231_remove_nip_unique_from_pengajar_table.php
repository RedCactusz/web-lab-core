<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE pengajar DROP CONSTRAINT IF EXISTS pengajar_nip_unique');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE pengajar ADD CONSTRAINT pengajar_nip_unique UNIQUE (nip)');
    }
};
