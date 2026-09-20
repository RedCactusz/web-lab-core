<?php

namespace App\Enums;

enum StatusAlatLog: string
{
    case Pengajuan = 'pengajuan';
    case Keluar = 'keluar';
    case Masuk = 'masuk';
    case Tambah = 'tambah';
    case Hapus = 'hapus';
    case Edit = 'edit';
}
