<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PraktikumController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:dosen,asisten'])->group(function (): void {
    Route::get('/me',                   [AuthController::class, 'me']);
    Route::post('/logout',              [AuthController::class, 'logout']);
    Route::get('/mahasiswa',            [MahasiswaController::class, 'index']);
    Route::put('/mahasiswa/{mahasiswa}', [MahasiswaController::class, 'update']);
    Route::post('/mahasiswa/import',    [MahasiswaController::class, 'importCsv']);
    Route::get('/praktikum',            [PraktikumController::class, 'index']);
});
