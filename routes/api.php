<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::post('get-token', [AuthController::class, 'getToken']);

Route::middleware(['auth:sanctum', 'check.api.access'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('mahasiswa-by-nim', [App\Http\Controllers\Api\MahasiswaController::class, 'index'])->name('api.mahasiswa-by-nim');

        Route::prefix('feeder')->group(function () {
            Route::post('prodi', [App\Http\Controllers\Api\Feeder\ReferensiController::class, 'prodi'])->name('api.feeder.prodi');
        });
    });
});

