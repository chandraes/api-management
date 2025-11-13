<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::post('get-token', [AuthController::class, 'getToken']);

Route::middleware(['auth:sanctum', 'check.api.access'])->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('mahasiswa-by-nim', [App\Http\Controllers\Api\MahasiswaController::class, 'index'])->name('api.mahasiswa-by-nim');
        
        // ✅ GET semua mahasiswa
        Route::get('mahasiswa-by-id-prodi', [App\Http\Controllers\Api\MahasiswaController::class, 'all_mahasiswa'])->name('api.mahasiswa-by-prodi');
        // ✅ GET mahasiswa by id_reg
        Route::get('mahasiswa-by-id-reg', [App\Http\Controllers\Api\MahasiswaController::class, 'mahasiswa_by_id_reg'])->name('api.mahasiswa-by-id-reg');
        
        
        Route::prefix('feeder')->group(function () {
            Route::post('prodi', [App\Http\Controllers\Api\Feeder\ReferensiController::class, 'prodi'])->name('api.feeder.prodi');
            // ✅ GET semua prodi
            Route::get('program-studi', [App\Http\Controllers\Api\Feeder\ReferensiController::class, 'get_prodi'])
                ->name('api.feeder.program-studi');

            // ✅ GET detail prodi by id_prodi
            Route::get('program-studi/{id_prodi}', [App\Http\Controllers\Api\Feeder\ReferensiController::class, 'informasi_prodi'])
                ->name('api.feeder.program-studi.detail');
        });
    });
});

