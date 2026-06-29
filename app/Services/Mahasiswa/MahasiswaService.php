<?php

namespace App\Services\Mahasiswa;

use App\Entities\Mahasiswa;
use App\Entities\Nilai;
use App\Entities\Peminjaman;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaService
{
    public function getMyProfile(Mahasiswa $mahasiswa): Mahasiswa
    {
        return $mahasiswa->load(['user', 'praktikum']);
    }

    public function getMyGrades(Mahasiswa $mahasiswa): Collection
    {
        return Nilai::with(['praktikum'])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getMyPeminjaman(Mahasiswa $mahasiswa): Collection
    {
        return Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createPeminjaman(Mahasiswa $mahasiswa, array $data): Peminjaman
    {
        return Peminjaman::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal_pengajuan' => $data['tanggal_pengajuan'] ?? now()->toDateString(),
            'nama_alat' => $data['nama_alat'],
            'jumlah' => $data['jumlah'],
            'tanggal_pinjam' => $data['tanggal_pinjam'] ?? null,
            'tanggal_kembali' => $data['tanggal_kembali'] ?? null,
            'status' => 'pending',
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    public function updatePeminjaman(Mahasiswa $mahasiswa, int $peminjamanId, array $data): ?Peminjaman
    {
        $peminjaman = Peminjaman::where('mahasiswa_id', $mahasiswa->id)
            ->where('id', $peminjamanId)
            ->first();

        if (!$peminjaman) {
            return null;
        }

        $peminjaman->update($data);

        return $peminjaman;
    }

    // Super Admin CRUD operations

    public function createWithUser(array $validated): Mahasiswa
    {
        return DB::transaction(function () use ($validated) {
            $nim = $validated['nim'];
            $angkatan = $this->extractAngkatanFromNim($nim);
            $email = $nim . '@student.upnyk.ac.id';

            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'username' => $nim,
            ]);

            $user->assignRole('mahasiswa');

            return Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'nama_lengkap' => $validated['nama_lengkap'],
                'angkatan' => $angkatan,
                'is_active' => $validated['is_active'] ?? true,
            ]);
        });
    }

    public function updateWithUser(Mahasiswa $mahasiswa, array $validated): Mahasiswa
    {
        return DB::transaction(function () use ($mahasiswa, $validated) {
            $nim = $validated['nim'];
            $angkatan = $this->extractAngkatanFromNim($nim);
            $email = $nim . '@student.upnyk.ac.id';

            $userData = [
                'name' => $validated['nama_lengkap'],
                'email' => $email,
            ];

            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $mahasiswa->user->update($userData);

            $mahasiswa->update([
                'nim' => $nim,
                'nama_lengkap' => $validated['nama_lengkap'],
                'angkatan' => $angkatan,
                'is_active' => $validated['is_active'] ?? $mahasiswa->is_active,
            ]);

            return $mahasiswa->fresh('user');
        });
    }

    public function importFromCsv(\Illuminate\Http\UploadedFile $file): array
    {
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            throw new \Exception('Gagal membuka file CSV');
        }

        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $headerChecked = false;
        $rowNumber = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $rowNumber++;

                // Skip baris kosong
                if (empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                    continue;
                }

                // Deteksi header di baris pertama
                if (!$headerChecked) {
                    $firstCell = strtolower(trim($row[0] ?? ''));
                    if (in_array($firstCell, ['nama', 'nama_lengkap', 'name', 'nim', 'no'])) {
                        $headerChecked = true;
                        continue;
                    }
                    $headerChecked = true;
                }

                $nama = trim($row[0] ?? '');
                $nim = trim($row[1] ?? '');

                // Validasi nama
                if (empty($nama)) {
                    $results['failed']++;
                    $results['errors'][] = "Baris {$rowNumber}: Nama kosong";
                    continue;
                }

                // Validasi NIM
                if (empty($nim)) {
                    $results['failed']++;
                    $results['errors'][] = "Baris {$rowNumber}: NIM kosong";
                    continue;
                }

                // Cek duplikat NIM
                if (Mahasiswa::where('nim', $nim)->exists()) {
                    $results['failed']++;
                    $results['errors'][] = "Baris {$rowNumber}: NIM {$nim} sudah terdaftar";
                    continue;
                }

                // Create mahasiswa
                $this->createWithUser([
                    'nim' => $nim,
                    'nama_lengkap' => $nama,
                    'password' => $nim,
                    'is_active' => true,
                ]);

                $results['success']++;
            }

            fclose($handle);
            DB::commit();

            return $results;

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            throw $e;
        }
    }

    private function extractAngkatanFromNim(string $nim): ?int
    {
        // Auto-extract angkatan dari NIM (format: 117190045 → 2017, 219190045 → 2019)
        // Digit ke-3 dan ke-4 adalah tahun angkatan
        if (strlen($nim) >= 5) {
            $yearDigits = substr($nim, 2, 2);
            $century = (int)substr($nim, 0, 1) === 1 ? 2000 : 1900;
            return $century + (int)$yearDigits;
        }

        return null;
    }
}
