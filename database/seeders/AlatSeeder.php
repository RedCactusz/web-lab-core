<?php

namespace Database\Seeders;

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            // Alat serial pabrik: satu baris per unit (jumlah = 1)
            ['inventaris' => 'TS-001', 'nama_alat' => 'Total Station', 'merk' => 'Leica', 'tipe' => 'TS06 Plus', 'serial_number' => 'SN-TS06-2019-041', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari A1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'TS-002', 'nama_alat' => 'Total Station', 'merk' => 'Leica', 'tipe' => 'TS06 Plus', 'serial_number' => 'SN-TS06-2019-042', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari A1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'TS-003', 'nama_alat' => 'Total Station', 'merk' => 'Trimble', 'tipe' => 'S5', 'serial_number' => 'SN-S5-2020-117', 'jumlah' => 1, 'kondisi' => [['status' => 'maintenance', 'jumlah' => 1, 'catatan' => [['komponen' => 'baterai', 'keterangan' => 'tidak mengisi']]]], 'lokasi_penyimpanan' => 'Lemari A2', 'ketersediaan' => 'perbaikan'],
            ['inventaris' => 'TH-001', 'nama_alat' => 'Theodolite', 'merk' => 'Topcon', 'tipe' => 'DT-209', 'serial_number' => 'SN-DT209-2018-233', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari B1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'TH-002', 'nama_alat' => 'Theodolite', 'merk' => 'Sokkia', 'tipe' => 'DT-630', 'serial_number' => 'SN-DT630-2017-089', 'jumlah' => 1, 'kondisi' => [['status' => 'rusak_ringan', 'jumlah' => 1, 'catatan' => [['komponen' => 'teropong', 'keterangan' => 'pecah'], ['komponen' => 'pengunci', 'keterangan' => 'lepas'], ['komponen' => 'tali', 'keterangan' => 'putus']]]], 'lokasi_penyimpanan' => 'Lemari B2', 'ketersediaan' => 'perbaikan'],
            ['inventaris' => 'WP-001', 'nama_alat' => 'Waterpass', 'merk' => 'Nikon', 'tipe' => 'AC-2S', 'serial_number' => 'SN-AC2S-2021-012', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari C1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'WP-002', 'nama_alat' => 'Waterpass', 'merk' => 'Nikon', 'tipe' => 'AC-2S', 'serial_number' => 'SN-AC2S-2021-013', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari C1', 'ketersediaan' => 'dipinjam'],
            ['inventaris' => 'WP-003', 'nama_alat' => 'Waterpass', 'merk' => 'Sokkia', 'tipe' => 'B40A', 'serial_number' => 'SN-B40A-2019-064', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari C2', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'GNSS-001', 'nama_alat' => 'GPS Geodetik', 'merk' => 'Trimble', 'tipe' => 'R2s', 'serial_number' => 'SN-R2S-2022-003', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari G1', 'ketersediaan' => 'dipinjam'],
            ['inventaris' => 'GNSS-002', 'nama_alat' => 'GPS Geodetik', 'merk' => 'Leica', 'tipe' => 'GS18', 'serial_number' => 'SN-GS18-2021-412', 'jumlah' => 1, 'kondisi' => [['status' => 'maintenance', 'jumlah' => 1, 'catatan' => [['komponen' => 'kalibrasi', 'keterangan' => 'sedang diproses']]]], 'lokasi_penyimpanan' => 'Lemari G2', 'ketersediaan' => 'perbaikan'],
            ['inventaris' => 'TB-001', 'nama_alat' => 'Tribach', 'merk' => 'Leica', 'tipe' => 'GDF322', 'serial_number' => 'SN-GDF-2021-336', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari K1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'TB-002', 'nama_alat' => 'Tribach', 'merk' => 'Leica', 'tipe' => 'GDF322', 'serial_number' => 'SN-GDF-2021-337', 'jumlah' => 1, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari K1', 'ketersediaan' => 'dipinjam'],
            // Barang identik massal: satu baris per kode (jumlah > 1)
            ['inventaris' => 'ST-001', 'nama_alat' => 'Statif', 'merk' => 'Generic', 'tipe' => 'Kayu', 'serial_number' => '-', 'jumlah' => 15, 'kondisi' => [['status' => 'baik', 'jumlah' => 12, 'catatan' => []], ['status' => 'rusak_ringan', 'jumlah' => 3, 'catatan' => [['komponen' => 'kaki', 'keterangan' => 'goyang']]]], 'lokasi_penyimpanan' => 'Rak D1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'ST-002', 'nama_alat' => 'Statif', 'merk' => 'Generic', 'tipe' => 'Aluminium', 'serial_number' => '-', 'jumlah' => 10, 'kondisi' => [['status' => 'baik', 'jumlah' => 10, 'catatan' => []]], 'lokasi_penyimpanan' => 'Rak D2', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'RU-001', 'nama_alat' => 'Rambu Ukur', 'merk' => 'Sokkia', 'tipe' => '3m', 'serial_number' => '-', 'jumlah' => 8, 'kondisi' => [['status' => 'baik', 'jumlah' => 8, 'catatan' => []]], 'lokasi_penyimpanan' => 'Rak E1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'RU-002', 'nama_alat' => 'Rambu Ukur', 'merk' => 'Topcon', 'tipe' => '5m', 'serial_number' => '-', 'jumlah' => 3, 'kondisi' => [['status' => 'baik', 'jumlah' => 1, 'catatan' => []], ['status' => 'rusak_ringan', 'jumlah' => 2, 'catatan' => [['komponen' => 'skala', 'keterangan' => 'perlu pengecekan']]]], 'lokasi_penyimpanan' => 'Rak E2', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'PR-001', 'nama_alat' => 'Prisma Reflektor', 'merk' => 'Leica', 'tipe' => 'GPR1', 'serial_number' => '-', 'jumlah' => 6, 'kondisi' => [['status' => 'baik', 'jumlah' => 6, 'catatan' => []]], 'lokasi_penyimpanan' => 'Lemari F1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'MT-001', 'nama_alat' => 'Meteran', 'merk' => 'Stanley', 'tipe' => '50m', 'serial_number' => '-', 'jumlah' => 10, 'kondisi' => [['status' => 'baik', 'jumlah' => 8, 'catatan' => []], ['status' => 'rusak_ringan', 'jumlah' => 2, 'catatan' => [['komponen' => 'kait', 'keterangan' => 'patah']]]], 'lokasi_penyimpanan' => 'Laci H1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'MT-002', 'nama_alat' => 'Meteran', 'merk' => 'Stanley', 'tipe' => '5m', 'serial_number' => '-', 'jumlah' => 15, 'kondisi' => [['status' => 'baik', 'jumlah' => 15, 'catatan' => []]], 'lokasi_penyimpanan' => 'Laci H2', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'PK-001', 'nama_alat' => 'Patok', 'merk' => 'Custom', 'tipe' => 'Besi 50cm', 'serial_number' => '-', 'jumlah' => 50, 'kondisi' => [['status' => 'baik', 'jumlah' => 45, 'catatan' => []], ['status' => 'rusak_ringan', 'jumlah' => 5, 'catatan' => [['komponen' => 'umum', 'keterangan' => 'bengkok']]]], 'lokasi_penyimpanan' => 'Gudang I1', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'PK-002', 'nama_alat' => 'Patok', 'merk' => 'Custom', 'tipe' => 'Kayu 30cm', 'serial_number' => '-', 'jumlah' => 100, 'kondisi' => [['status' => 'baik', 'jumlah' => 100, 'catatan' => []]], 'lokasi_penyimpanan' => 'Gudang I2', 'ketersediaan' => 'tersedia'],
            ['inventaris' => 'PY-001', 'nama_alat' => 'Payung Teropong', 'merk' => 'Generic', 'tipe' => 'UV Protection', 'serial_number' => '-', 'jumlah' => 5, 'kondisi' => [['status' => 'baik', 'jumlah' => 3, 'catatan' => []], ['status' => 'rusak_ringan', 'jumlah' => 2, 'catatan' => [['komponen' => 'kain', 'keterangan' => 'sobek']]]], 'lokasi_penyimpanan' => 'Rak J1', 'ketersediaan' => 'tersedia'],
        ];

        foreach ($data as $item) {
            Alat::updateOrCreate(
                ['inventaris' => $item['inventaris']],
                $item
            );
        }
    }
}
