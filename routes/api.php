<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mahasiswa', [App\Http\Controllers\MahasiswaController::class, 'index']);
Route::get('/mahasiswa/{id}', [App\Http\Controllers\MahasiswaController::class, 'show']);
Route::post('/mahasiswa', [App\Http\Controllers\MahasiswaController::class, 'store']);
Route::put('/mahasiswa/{id}', [App\Http\Controllers\MahasiswaController::class, 'update']);
Route::delete('/mahasiswa/{id}', [App\Http\Controllers\MahasiswaController::class, 'destroy']);

Route::get('/dosen', [App\Http\Controllers\DosenController::class, 'index']);
Route::get('/dosen/{id}', [App\Http\Controllers\DosenController::class, 'show']);
Route::post('/dosen', [App\Http\Controllers\DosenController::class, 'store']);
Route::put('/dosen/{id}', [App\Http\Controllers\DosenController::class, 'update']);
Route::delete('/dosen/{id}', [App\Http\Controllers\DosenController::class, 'destroy']);