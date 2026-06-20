<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Mahasiswa\MahasiswaController;
use App\Http\Controllers\Public\PublicContentController;
use App\Http\Controllers\Public\PublicPeminjamanController;
use App\Http\Controllers\Public\PraktikumSelectorController;
use Illuminate\Support\Facades\Route;

Route::post('/login/mahasiswa', [AuthController::class, 'loginMahasiswa']);
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

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware(['role:mahasiswa'])->prefix('mahasiswa')->group(function () {
        Route::get('/me', [MahasiswaController::class, 'me']);
        Route::get('/grades', [MahasiswaController::class, 'grades']);
        Route::get('/peminjaman', [MahasiswaController::class, 'peminjamanIndex']);
        Route::post('/peminjaman', [MahasiswaController::class, 'peminjamanStore']);
        Route::put('/peminjaman/{id}', [MahasiswaController::class, 'peminjamanUpdate']);
    });
});
