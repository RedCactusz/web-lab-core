<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MahasiswaController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:dosen,asisten'])->group(function (): void {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
});
