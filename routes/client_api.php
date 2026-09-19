<?php

use App\Http\Controllers\Client\AlatController;
use App\Http\Controllers\Client\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:mahasiswa,asisten'])->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/alat',                    [AlatController::class, 'index']);
    Route::post('/alat/{alat}/pinjam',     [AlatController::class, 'pinjam']);
    Route::post('/alat/{alat}/kembali',    [AlatController::class, 'kembali']);
    Route::get('/alat-log',                [AlatController::class, 'riwayat']);
});
