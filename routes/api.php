<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\MapelController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\AuthController;

Route::post('login', [AuthController::class, 'login']);
Route::apiResource('guru', GuruController::class);
Route::apiResource('mapel', MapelController::class);
Route::apiResource('kelas', KelasController::class);
Route::apiResource('siswa', SiswaController::class);
Route::apiResource('jadwal', JadwalController::class);

Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login']);
Route::apiResource('guru', GuruController::class);