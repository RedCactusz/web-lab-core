<?php

namespace Database\Seeders;

use App\Entities\Inventaris;
use Illuminate\Database\Seeder;

class InventarisSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode_alat' => 'TS-001', 'nama' => 'Total Station', 'kategori' => 'surveying', 'merk' => 'Leica', 'tipe' => 'TS06 Plus', 'kondisi' => 'baik', 'jumlah' => 3, 'lokasi' => 'Lemari A1', 'keterangan' => 'Akurasi 2", dilengkapi EDM reflectorless'],
            ['kode_alat' => 'TS-002', 'nama' => 'Total Station', 'kategori' => 'surveying', 'merk' => 'Trimble', 'tipe' => 'S5', 'kondisi' => 'baik', 'jumlah' => 2, 'lokasi' => 'Lemari A2', 'keterangan' => 'Dengan teknologi Trimble VISION'],
            ['kode_alat' => 'TH-001', 'nama' => 'Theodolite', 'kategori' => 'surveying', 'merk' => 'Topcon', 'tipe' => 'DT-209', 'kondisi' => 'baik', 'jumlah' => 4, 'lokasi' => 'Lemari B1', 'keterangan' => 'Theodolite digital, akurasi 5"'],
            ['kode_alat' => 'TH-002', 'nama' => 'Theodolite', 'kategori' => 'surveying', 'merk' => 'Sokkia', 'tipe' => 'DT-630', 'kondisi' => 'rusak_ringan', 'jumlah' => 2, 'lokasi' => 'Lemari B2', 'keterangan' => 'Perlu kalibrasi ulang sumbu horizontal'],
            ['kode_alat' => 'WP-001', 'nama' => 'Waterpass', 'kategori' => 'surveying', 'merk' => 'Nikon', 'tipe' => 'AC-2S', 'kondisi' => 'baik', 'jumlah' => 5, 'lokasi' => 'Lemari C1', 'keterangan' => 'Automatic level, magnifikasi 32x'],
            ['kode_alat' => 'WP-002', 'nama' => 'Waterpass', 'kategori' => 'surveying', 'merk' => 'Sokkia', 'tipe' => 'B40A', 'kondisi' => 'baik', 'jumlah' => 3, 'lokasi' => 'Lemari C2', 'keterangan' => 'Kompensator magnetik'],
            ['kode_alat' => 'ST-001', 'nama' => 'Statif', 'kategori' => 'aksesoris', 'merk' => 'Generic', 'tipe' => 'Kayu', 'kondisi' => 'baik', 'jumlah' => 15, 'lokasi' => 'Rak D1', 'keterangan' => 'Statif kayu untuk Total Station & Theodolite'],
            ['kode_alat' => 'ST-002', 'nama' => 'Statif', 'kategori' => 'aksesoris', 'merk' => 'Generic', 'tipe' => 'Aluminium', 'kondisi' => 'baik', 'jumlah' => 10, 'lokasi' => 'Rak D2', 'keterangan' => 'Statif aluminium untuk Waterpass'],
            ['kode_alat' => 'RU-001', 'nama' => 'Rambu Ukur', 'kategori' => 'aksesoris', 'merk' => 'Sokkia', 'tipe' => '3m', 'kondisi' => 'baik', 'jumlah' => 8, 'lokasi' => 'Rak E1', 'keterangan' => 'Rambu ukur aluminium 3 meter, skala mm'],
            ['kode_alat' => 'RU-002', 'nama' => 'Rambu Ukur', 'kategori' => 'aksesoris', 'merk' => 'Topcon', 'tipe' => '5m', 'kondisi' => 'rusak_ringan', 'jumlah' => 3, 'lokasi' => 'Rak E2', 'keterangan' => 'Rambu ukur teleskopik 5 meter, perlu pengecekan skala'],
            ['kode_alat' => 'PR-001', 'nama' => 'Prisma Reflektor', 'kategori' => 'aksesoris', 'merk' => 'Leica', 'tipe' => 'GPR1', 'kondisi' => 'baik', 'jumlah' => 6, 'lokasi' => 'Lemari F1', 'keterangan' => 'Prisma single, konstanta -34.4mm'],
            ['kode_alat' => 'PR-002', 'nama' => 'Prisma Reflektor', 'kategori' => 'aksesoris', 'merk' => 'Trimble', 'tipe' => 'Standard', 'kondisi' => 'baik', 'jumlah' => 4, 'lokasi' => 'Lemari F2', 'keterangan' => 'Prisma dengan tribrach'],
            ['kode_alat' => 'GNSS-001', 'nama' => 'GPS Geodetik', 'kategori' => 'surveying', 'merk' => 'Trimble', 'tipe' => 'R2s', 'kondisi' => 'baik', 'jumlah' => 2, 'lokasi' => 'Lemari G1', 'keterangan' => 'GNSS receiver multi-frequency, RTK capable'],
            ['kode_alat' => 'GNSS-002', 'nama' => 'GPS Geodetik', 'kategori' => 'surveying', 'merk' => 'Leica', 'tipe' => 'GS18', 'kondisi' => 'maintenance', 'jumlah' => 1, 'lokasi' => 'Lemari G2', 'keterangan' => 'Sedang dalam proses kalibrasi'],
            ['kode_alat' => 'MT-001', 'nama' => 'Meteran', 'kategori' => 'perlengkapan', 'merk' => 'Stanley', 'tipe' => '50m', 'kondisi' => 'baik', 'jumlah' => 10, 'lokasi' => 'Laci H1', 'keterangan' => 'Meteran baja 50 meter'],
            ['kode_alat' => 'MT-002', 'nama' => 'Meteran', 'kategori' => 'perlengkapan', 'merk' => 'Stanley', 'tipe' => '5m', 'kondisi' => 'baik', 'jumlah' => 15, 'lokasi' => 'Laci H2', 'keterangan' => 'Meteran saku 5 meter'],
            ['kode_alat' => 'PK-001', 'nama' => 'Patok', 'kategori' => 'perlengkapan', 'merk' => 'Custom', 'tipe' => 'Besi 50cm', 'kondisi' => 'baik', 'jumlah' => 50, 'lokasi' => 'Gudang I1', 'keterangan' => 'Patok besi untuk titik kontrol'],
            ['kode_alat' => 'PK-002', 'nama' => 'Patok', 'kategori' => 'perlengkapan', 'merk' => 'Custom', 'tipe' => 'Kayu 30cm', 'kondisi' => 'baik', 'jumlah' => 100, 'lokasi' => 'Gudang I2', 'keterangan' => 'Patok kayu untuk patok sementara'],
            ['kode_alat' => 'PY-001', 'nama' => 'Payung Teropong', 'kategori' => 'perlengkapan', 'merk' => 'Generic', 'tipe' => 'UV Protection', 'kondisi' => 'rusak_ringan', 'jumlah' => 5, 'lokasi' => 'Rak J1', 'keterangan' => 'Payung pelindung alat dari panas/hujan, 2 perlu diganti'],
            ['kode_alat' => 'TB-001', 'nama' => 'Tribach', 'kategori' => 'aksesoris', 'merk' => 'Leica', 'tipe' => 'GDF322', 'kondisi' => 'baik', 'jumlah' => 4, 'lokasi' => 'Lemari K1', 'keterangan' => 'Tribach untuk mounting prisma & target'],
        ];

        foreach ($data as $item) {
            Inventaris::firstOrCreate(
                ['kode_alat' => $item['kode_alat']],
                $item
            );
        }
    }
}
