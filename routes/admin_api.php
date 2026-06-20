<?php

use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\PengajarController as AdminPengajarController;
use App\Http\Controllers\Admin\PraktikumController as AdminPraktikumController;
use App\Http\Controllers\SuperAdmin\SuperAdminAuthController;
use App\Http\Controllers\SuperAdmin\InventarisController;
use App\Http\Controllers\SuperAdmin\PeminjamanController;
use App\Http\Controllers\SuperAdmin\PengajarController;
use App\Http\Controllers\SuperAdmin\MahasiswaController;
use App\Http\Controllers\SuperAdmin\PraktikumController;
use App\Http\Controllers\SuperAdmin\NewsController;
use App\Http\Controllers\SuperAdmin\GalleryController;
use App\Http\Controllers\SuperAdmin\AgendaController;
use App\Http\Controllers\SuperAdmin\PartnerController;
use App\Http\Controllers\SuperAdmin\KerjasamaController;
use App\Http\Controllers\SuperAdmin\PraktikumManagementController;
use App\Http\Controllers\Pengajar\PeminjamanController as PengajarPeminjamanController;
use App\Http\Controllers\Pengajar\PengajarController as PengajarPenilaianController;
use App\Http\Controllers\Public\PublicContentController;
use App\Http\Controllers\Public\PublicPeminjamanController;
use App\Http\Controllers\Public\PraktikumSelectorController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login/pengajar', [AuthController::class, 'loginPengajar']);
Route::post('/register/pengajar', [AuthController::class, 'registerPengajar']);
Route::post('/super-admin/login', [SuperAdminAuthController::class, 'login']);
Route::post('/super-admin/register', [SuperAdminAuthController::class, 'register']);
Route::get('/selector/praktikum', [PraktikumSelectorController::class, 'listPraktikum']);

Route::prefix('public')->group(function () {
    Route::get('/gallery', [PublicContentController::class, 'gallery']);
    Route::get('/news', [PublicContentController::class, 'news']);
    Route::get('/agenda', [PublicContentController::class, 'agenda']);
    Route::get('/partners', [PublicContentController::class, 'partners']);
    Route::get('/kerjasama', [PublicContentController::class, 'kerjasama']);
    Route::get('/inventaris', [PublicPeminjamanController::class, 'inventaris']);
    Route::post('/peminjaman', [PublicPeminjamanController::class, 'store']);
    Route::get('/peminjaman', [PublicPeminjamanController::class, 'indexByNim']);
});

Route::middleware(['auth:sanctum', 'role:admin|super-admin'])->prefix('admin')->group(function () {
    Route::apiResource('pengajar', AdminPengajarController::class);
    Route::apiResource('mahasiswa', AdminMahasiswaController::class);
    Route::apiResource('praktikum', AdminPraktikumController::class);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('pengajar')->group(function () {
        Route::get('/me', [PengajarPenilaianController::class, 'me']);
        Route::get('/{praktikumSlug}/students', [PengajarPenilaianController::class, 'students']);
        Route::get('/{praktikumSlug}/students/{nim}', [PengajarPenilaianController::class, 'studentDetail']);
        Route::put('/{praktikumSlug}/grades/{nim}', [PengajarPenilaianController::class, 'updateGrade']);
        Route::get('/{praktikumSlug}/stats', [PengajarPenilaianController::class, 'stats']);
        Route::get('/{praktikumSlug}/minggu', [PengajarPenilaianController::class, 'listMinggu']);
        Route::get('/{praktikumSlug}/minggu/{mingguId}/parameters', [PengajarPenilaianController::class, 'getMingguParameters']);
        Route::get('/peminjaman', [PengajarPeminjamanController::class, 'index']);
        Route::put('/peminjaman/{id}', [PengajarPeminjamanController::class, 'updateStatus']);
    });
});

Route::middleware('auth:sanctum')->prefix('super-admin')->group(function () {
    Route::get('/stats', [SuperAdminAuthController::class, 'getStats']);
    Route::get('/check-username', [SuperAdminAuthController::class, 'checkUsername']);
    Route::apiResource('inventaris', InventarisController::class);
    Route::apiResource('peminjaman', PeminjamanController::class);
    Route::apiResource('pengajar', PengajarController::class);
    Route::apiResource('mahasiswa', MahasiswaController::class);
    Route::post('/mahasiswa/import-csv', [MahasiswaController::class, 'importCsv']);
    Route::prefix('praktikum')->group(function () {
        Route::get('/{slug}/detail', [PraktikumManagementController::class, 'detail']);
        Route::get('/{slug}/minggu', [PraktikumManagementController::class, 'listMinggu']);
        Route::post('/{slug}/minggu/generate', [PraktikumManagementController::class, 'generateMinggu']);
        Route::put('/minggu/{id}', [PraktikumManagementController::class, 'updateMinggu']);
        Route::post('/minggu/{id}/parameter', [PraktikumManagementController::class, 'addParameter']);
        Route::put('/parameter/{id}', [PraktikumManagementController::class, 'updateParameter']);
        Route::delete('/parameter/{id}', [PraktikumManagementController::class, 'deleteParameter']);
        Route::post('/minggu/{id}/generate', [PraktikumManagementController::class, 'generatePenilaian']);
        Route::get('/{slug}/preview', [PraktikumManagementController::class, 'preview']);
        Route::get('/{slug}/jadwal', [PraktikumManagementController::class, 'listJadwal']);
        Route::post('/{slug}/jadwal', [PraktikumManagementController::class, 'storeJadwal']);
        Route::put('/jadwal/{id}', [PraktikumManagementController::class, 'updateJadwal']);
        Route::delete('/jadwal/{id}', [PraktikumManagementController::class, 'deleteJadwal']);
        Route::post('/{slug}/kelompok/assign', [PraktikumManagementController::class, 'assignMahasiswaToKelompok']);
        Route::post('/{slug}/kelompok/remove', [PraktikumManagementController::class, 'removeMahasiswaFromKelompok']);
    });
    Route::apiResource('praktikum', PraktikumController::class);
    Route::apiResource('news', NewsController::class);
    Route::apiResource('gallery', GalleryController::class);
    Route::apiResource('agenda', AgendaController::class);
    Route::apiResource('partners', PartnerController::class);
    Route::apiResource('kerjasama', KerjasamaController::class);
});
