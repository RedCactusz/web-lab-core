<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Entities\Mahasiswa;
use App\Http\Requests\SuperAdmin\MahasiswaRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function index(): JsonResponse
    {
        return $this->successResponse(Mahasiswa::with('user')->orderBy('nama_lengkap')->get(), 'Mahasiswa retrieved');
    }

    public function store(MahasiswaRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated) {
            $nim = $validated['nim'];
            $angkatan = strlen($nim) >= 5 ? 2000 + (int)substr($nim, 3, 2) : null;
            $email = $nim . '@student.upnyk.ac.id';

            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $email,
                'password' => Hash::make($validated['password']),
                'username' => $nim,
            ]);

            $user->assignRole('mahasiswa');

            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $nim,
                'nama_lengkap' => $validated['nama_lengkap'],
                'angkatan' => $angkatan,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return $this->successResponse($mahasiswa->load('user'), 'Mahasiswa created successfully', 201);
        });
    }

    public function show($id): JsonResponse
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);
        return $this->successResponse($mahasiswa, 'Mahasiswa retrieved');
    }

    public function update(MahasiswaRequest $request, $id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $validated = $request->validated();

        return DB::transaction(function () use ($mahasiswa, $validated) {
            $nim = $validated['nim'];
            $angkatan = strlen($nim) >= 5 ? 2000 + (int)substr($nim, 3, 2) : null;
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

            return $this->successResponse($mahasiswa->load('user'), 'Mahasiswa updated successfully');
        });
    }

    public function destroy($id): JsonResponse
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->user->delete();

        return $this->successResponse(null, 'Mahasiswa berhasil dihapus');
    }

    public function importCsv(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        if ($handle === false) {
            return $this->errorResponse('Gagal membaca file CSV', 400);
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

                if (empty($nama)) {
                    $results['failed']++;
                    $results['errors'][] = "Baris {$rowNumber}: Nama kosong";
                    continue;
                }

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

                // Auto-extract angkatan dari NIM (format: 117190045 → 2019)
                $angkatan = strlen($nim) >= 5 ? 2000 + (int)substr($nim, 3, 2) : null;
                $email = $nim . '@student.upnyk.ac.id';
                $password = $nim;

                $user = User::create([
                    'name' => $nama,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'username' => $nim,
                ]);

                $user->assignRole('mahasiswa');

                Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $nim,
                    'nama_lengkap' => $nama,
                    'angkatan' => $angkatan,
                    'is_active' => true,
                ]);

                $results['success']++;
            }

            fclose($handle);
            DB::commit();

            return $this->successResponse([
                'message' => 'Import selesai',
                'results' => $results,
            ], 'Import CSV selesai');

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return $this->errorResponse('Gagal import: ' . $e->getMessage(), 500, $results);
        }
    }
}
