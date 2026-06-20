<?php

namespace App\Services\Public;

use App\Entities\Praktikum;
use Illuminate\Support\Collection;

class PraktikumSelectorService
{
    public function listPraktikum(): Collection
    {
        return Praktikum::active()
            ->orderBy('nama')
            ->get(['id', 'kode', 'nama', 'slug', 'jumlah_plug']);
    }
}
