<?php

use App\Http\Controllers\Client\AlatController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:mahasiswa,asisten'])->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/alat', [AlatController::class, 'index']);
    Route::get('/peminjaman', [PeminjamanController::class, 'index']);
    Route::get('/peminjaman/options', [PeminjamanController::class, 'options']);
    Route::post('/peminjaman', [PeminjamanController::class, 'store']);
});
