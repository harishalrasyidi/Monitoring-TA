<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KotaController;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DosbingDashboardController;
use App\Http\Controllers\KaprodiDashboardController;
use App\Http\Controllers\KoordinatorDashboardController;
use Illuminate\Support\Facades\Storage;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


//Home
Route::get('/', function() {
    $user = auth()->user();
    if ($user->role == 1) {
        return app(\App\Http\Controllers\KoordinatorDashboardController::class)->index(request());
    } elseif ($user->role == 2) {
        return app(\App\Http\Controllers\DosbingDashboardController::class)->index(request());
    } elseif ($user->role == 3) {
        return app(\App\Http\Controllers\MahasiswaDashboardController::class)->index(request());
    } elseif ($user->role == 4 || $user->role == 5) {
        return app(\App\Http\Controllers\KaprodiDashboardController::class)->index(request());
    } else {
        abort(403, 'Akses tidak diizinkan.');
    }
})->middleware(['auth', 'role:1,2,3,4,5'])->name('home');
Route::post('/kota-status', [App\Http\Controllers\HomeController::class, 'kota_status'])->middleware(['auth', 'role:3'])->name('kota.status');
Route::get('/{id}/file', [App\Http\Controllers\HomeController::class, 'showFile'])->name('home.showFile');
Route::get('/dashboard/kota-uji', [App\Http\Controllers\DashboardController::class, 'getKotaUji'])->name('dashboard.kota-uji');
Route::get('/kota/{id}/artefak', [KotaController::class, 'showArtefak'])->name('kota.artefak.detail');
Route::middleware(['auth', 'role:4,5'])->group(function () {
    Route::get('/kaprodi/yudisium-list', [App\Http\Controllers\KaprodiDashboardController::class, 'getKotaByYudisium'])->name('kaprodi.yudisium.list');
});
Route::get('/kaprodi/dashboard', [KaprodiDashboardController::class, 'index'])->name('kaprodi.dashboard');
Route::get('/koordinator/dashboard', [KoordinatorDashboardController::class, 'index'])->name('koordinator.dashboard');
Route::get('/dosbing/dashboard', [DosbingDashboardController::class, 'index'])->name('dosbing.dashboard');
//Kota
Route::get('/kota', [App\Http\Controllers\KotaController::class, 'index'])->middleware(['auth', 'role:1,2,3'])->name('kota');
Route::get('/kota/create', [App\Http\Controllers\KotaController::class, 'create'])->middleware(['auth', 'role:1,2'])->name('kota.create'); //menambahkan data
Route::get('/kota/{id}', [App\Http\Controllers\KotaController::class, 'detail'])->middleware(['auth', 'role:1,2'])->name('kota.detail');
Route::post('/kota/store', [App\Http\Controllers\KotaController::class, 'store'])->middleware(['auth', 'role:1,2'])->name('kota.store');
Route::get('/kota/edit/{id}', [App\Http\Controllers\KotaController::class, 'edit'])->middleware(['auth', 'role:1,2'])->name('kota.edit');
Route::put('/kota/update/{id}', [App\Http\Controllers\KotaController::class, 'update'])->middleware(['auth', 'role:1,2'])->name('kota.update');
Route::get('/kota/search', [App\Http\Controllers\KotaController::class, 'search'])->middleware(['auth', 'role:1,2'])->name('kota.search');
Route::delete('/kota/{id}', [App\Http\Controllers\KotaController::class, 'destroy'])->middleware(['auth', 'role:1,2'])->name('kota.destroy');
Route::post('/store_status', [App\Http\Controllers\KotaController::class, 'store_status'])->middleware(['auth', 'role:2'])->name('store_status');
Route::get('/kota/{id}/file', [App\Http\Controllers\KotaController::class, 'showFile'])->middleware(['auth', 'role:1, 2'])->name('kota.showFile');


//Timeline
Route::get('/timeline', [App\Http\Controllers\TimelineController::class, 'index'])->middleware(['auth', 'role:1,2,3,4'])->name('timeline');
Route::get('/timeline/detail/{id}', [App\Http\Controllers\TimelineController::class, 'detail'])->middleware(['auth', 'role:1'])->name('timeline.detail');
Route::get('/timeline/create', [App\Http\Controllers\TimelineController::class, 'create'])->middleware(['auth', 'role:1'])->name('timeline.create'); //menambahkan data
Route::get('/timeline/{id}', [App\Http\Controllers\TimelineController::class, 'detail'])->middleware(['auth', 'role:1'])->name('timeline.detail');
Route::post('/timeline/store', [App\Http\Controllers\TimelineController::class, 'store'])->middleware(['auth', 'role:1'])->name('timeline.store');
Route::get('/timeline/edit/{id}', [App\Http\Controllers\TimelineController::class, 'edit'])->middleware(['auth', 'role:1'])->name('timeline.edit');
Route::put('/timeline/update/{id}', [App\Http\Controllers\TimelineController::class, 'update'])->middleware(['auth', 'role:1'])->name('timeline.update');
Route::get('/timeline/search', [App\Http\Controllers\TimelineController::class, 'search'])->middleware(['auth', 'role:1'])->name('timeline.search');
Route::delete('/timeline/{id}', [App\Http\Controllers\TimelineController::class, 'destroy'])->middleware(['auth', 'role:1'])->name('timeline.destroy');
Route::get('/timeline', [App\Http\Controllers\TimelineController::class, 'index'])->name('timeline');
Route::get('/timeline/store', [App\Http\Controllers\TimelineController::class, 'store'])->name('timeline.store');

//Jadwal Kegiatan
Route::get('/kegiatan', [App\Http\Controllers\JadwalKegiatanController::class, 'index'])->middleware(['auth', 'role:2,3'])->name('kegiatan.index');
Route::get('/kegiatan/{id}', [App\Http\Controllers\JadwalKegiatanController::class, 'detail'])->middleware(['auth', 'role:2,3'])->name('kegiatan.detail');
Route::post('/jadwal-kegiatan', [App\Http\Controllers\JadwalKegiatanController::class, 'store_kegiatan'])->middleware(['auth', 'role:2,3'])->name('kegiatan.store_kegiatan');
Route::post('/metodologi/store', [App\Http\Controllers\JadwalKegiatanController::class, 'store_metodologi'])->middleware(['auth', 'role:2,3'])->name('metodologi.store');
Route::post('/metodologi/update/{id}', [App\Http\Controllers\JadwalKegiatanController::class, 'update_metodologi'])->middleware(['auth', 'role:2,3'])->name('metodologi.update');
Route::post('/status-kegiatan', [App\Http\Controllers\JadwalKegiatanController::class, 'store_status_kegiatan'])->middleware(['auth', 'role:2,3'])->name('kegiatan.storeStatusKegiatan');
Route::post('/events/edit', [App\Http\Controllers\JadwalKegiatanController::class, 'edit_Kegiatan'])->middleware(['auth', 'role:2,3'])->name('events.edit');
Route::post('/resources/edit', [App\Http\Controllers\JadwalKegiatanController::class, 'edit_resource'])->middleware(['auth', 'role:2,3'])->name('resources.edit');
Route::delete('/delete-event/{id}', [App\Http\Controllers\JadwalKegiatanController::class, 'destroy'])->middleware(['auth', 'role:2,3'])->name('events.destroy');
Route::post('/events/update', [App\Http\Controllers\JadwalKegiatanController::class, 'update'])->middleware(['auth', 'role:2,3'])->name('events.update');
// Route::post('/jadwal-kegiatan', [App\Http\Controllers\JadwalKegiatanController::class, 'storeJadwalKegiatan'])->middleware(['auth', 'role:2,3'])->name('kegiatan.storeJadwalKegiatan');

Route::delete('/delete-item', [App\Http\Controllers\JadwalKegiatanController::class, 'destroy'])->middleware(['auth', 'role:3'])->name('delete.item');

Route::get('/kegiatan/create', [App\Http\Controllers\JadwalKegiatanController::class, 'create'])->middleware(['auth', 'role:2,3'])->name('kegiatan.create');
Route::post('/kegiatan/store', [App\Http\Controllers\JadwalKegiatanController::class, 'store'])->middleware(['auth', 'role:2,3'])->name('kegiatan.store');
Route::put('/kegiatan/update/{id}', [App\Http\Controllers\JadwalKegiatanController::class, 'update'])->middleware(['auth', 'role:2,3'])->name('kegiatan.update');
//nanti ubah kalau error
// Route::get('/kegiatans', [App\Http\Controllers\KegiatanController::class, 'index'])->middleware(['auth', 'role:2,3'])->name('kegiatans.index');



//Artefak
Route::get('/artefak', [App\Http\Controllers\ArtefakController::class, 'index'])->middleware(['auth', 'role:1,3'])->name('artefak');
Route::get('/artefak/detail/{id}', [App\Http\Controllers\ArtefakController::class, 'detail'])->middleware(['auth', 'role:1'])->name('artefak.detail');
Route::get('/artefak/create', [App\Http\Controllers\ArtefakController::class, 'create'])->middleware(['auth', 'role:1'])->name('artefak.create'); //menambahkan data
Route::get('/artefak/{id}', [App\Http\Controllers\ArtefakController::class, 'detail'])->middleware(['auth', 'role:1'])->name('artefak.detail');
Route::post('/artefak/store', [App\Http\Controllers\ArtefakController::class, 'store'])->middleware(['auth', 'role:1'])->name('artefak.store');
Route::get('/artefak/edit/{id}', [App\Http\Controllers\ArtefakController::class, 'edit'])->middleware(['auth', 'role:1'])->name('artefak.edit');
Route::put('/artefak/update{id}', [App\Http\Controllers\ArtefakController::class, 'update'])->middleware(['auth', 'role:1'])->name('artefak.update');
Route::post('/artefak/search', [App\Http\Controllers\ArtefakController::class, 'search'])->middleware(['auth', 'role:1'])->name('artefak.search');
Route::delete('/artefak/{id}', [App\Http\Controllers\ArtefakController::class, 'destroy'])->middleware(['auth', 'role:1'])->name('artefak.destroy');
//Pengumpulan Artefak
Route::get('/artefak/{artefak_id}/submit', [App\Http\Controllers\SubmissionController::class, 'create'])->middleware(['auth', 'role:3'])->name('submissions.create');
Route::post('/artefak/{artefak_id}/submit', [App\Http\Controllers\SubmissionController::class, 'store'])->middleware(['auth', 'role:3'])->name('submissions.store');
Route::delete('submissions/{id}', [App\Http\Controllers\SubmissionController::class, 'destroy'])->middleware(['auth', 'role:3'])->name('submissions.destroy');


//Jadwal
Route::get('/jadwal', [App\Http\Controllers\JadwalController::class, 'index'])->middleware(['auth', 'role:1,3'])->name('jadwal');
Route::post('/jadwal/store', [App\Http\Controllers\JadwalController::class, 'store'])->middleware(['auth', 'role:1,3'])->name('jadwal.store');
Route::post('/jadwal/events', [App\Http\Controllers\JadwalController::class, 'events'])->middleware(['auth', 'role:1,3'])->name('jadwal.events');
Route::get('/jadwal/edit/{id}', [App\Http\Controllers\JadwalController::class, 'edit'])->middleware(['auth', 'role:1,3'])->name('jadwal.edit');
Route::put('/jadwal/update{id}', [App\Http\Controllers\JadwalController::class, 'update'])->middleware(['auth', 'role:1,3'])->name('jadwal.update');
Route::delete('/jadwal/{id}', [App\Http\Controllers\JadwalController::class, 'destroy'])->middleware(['auth', 'role:1,3'])->name('jadwal.destroy');


//Resume Bimbingan
Route::get('/resume', [App\Http\Controllers\ResumeBimbinganController::class, 'index'])->middleware(['auth', 'role:2,3'])->name('resume');
Route::get('/resume/detail/{id}', [App\Http\Controllers\ResumeBimbinganController::class, 'detail'])->middleware(['auth', 'role:2,3'])->name('resume.detail');
Route::get('/resume/create', [App\Http\Controllers\ResumeBimbinganController::class, 'create'])->middleware(['auth', 'role:2,3'])->name('resume.create'); //menambahkan data
Route::get('/resume/{id}', [App\Http\Controllers\ResumeBimbinganController::class, 'detail'])->middleware(['auth', 'role:2,3'])->name('resume.detail');
Route::post('/resume/store', [App\Http\Controllers\ResumeBimbinganController::class, 'store'])->middleware(['auth', 'role:2,3'])->name('resume.store');
Route::get('/resume/edit/{id}', [App\Http\Controllers\ResumeBimbinganController::class, 'edit'])->middleware(['auth', 'role:2,3'])->name('resume.edit');
Route::put('/resume/update/{id}', [App\Http\Controllers\ResumeBimbinganController::class, 'update'])->middleware(['auth', 'role:2,3'])->name('resume.update');
Route::post('/resume/search', [App\Http\Controllers\ResumeBimbinganController::class, 'search'])->middleware(['auth', 'role:2,3'])->name('resume.search');
Route::delete('/resume/{id}', [App\Http\Controllers\ResumeBimbinganController::class, 'destroy'])->middleware(['auth', 'role:2,3'])->name('resume.destroy');
Route::get('/resume/generate-pdf/{sesi_bimbingan}', [App\Http\Controllers\ResumeBimbinganController::class, 'generatePdf'])->middleware(['auth', 'role:3'])->name('resume.generatePdf');

// Yudisium
// Route::get('/yudisium', [App\Http\Controllers\YudisiumController::class, 'index'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.index');
Route::get('/yudisium/dashboard', [App\Http\Controllers\YudisiumController::class, 'dashboard'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.dashboard');
Route::get('/yudisium/create', [App\Http\Controllers\YudisiumController::class, 'create'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.create');
Route::post('/yudisium/store', [App\Http\Controllers\YudisiumController::class, 'store'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.store');
// Route::get('/yudisium/{id}', [App\Http\Controllers\YudisiumController::class, 'show'])->middleware(['auth', 'role:1,2,3,4,5'])->name('yudisium.show');
Route::get('/yudisium/{id}/edit', [App\Http\Controllers\YudisiumController::class, 'edit'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.edit');
Route::put('/yudisium/{id}/update', [App\Http\Controllers\YudisiumController::class, 'update'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.update');
Route::delete('/yudisium/{id}', [App\Http\Controllers\YudisiumController::class, 'destroy'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.destroy');
Route::get('/yudisium/export/excel', [App\Http\Controllers\YudisiumController::class, 'export'])->middleware(['auth', 'role:1,4,5'])->name('yudisium.export');
Route::get('/status-yudisium', [App\Http\Controllers\YudisiumController::class, 'status'])->middleware(['auth', 'role:2,3'])->name('yudisium.status');
Route::get('/yudisium/kelola', [App\Http\Controllers\YudisiumController::class, 'index'])->name('yudisium.kelola');
Route::get('/yudisium/detail/{id}', [App\Http\Controllers\YudisiumController::class, 'show'])->name('yudisium.detail');
// Route::post('/yudisium/generate', [App\Http\Controllers\YudisiumController::class, 'generate'])->name('yudisium.generate');
Route::get('/koordinator/yudisium-list', [App\Http\Controllers\KoordinatorDashboardController::class, 'getKotaByYudisium'])->name('koordinator.yudisium-list');

// History
Route::get('/history', [App\Http\Controllers\HistoryController::class, 'index'])->middleware(['auth', 'role:2'])->name('history.index');

//Katalog TA Routes
Route::middleware(['auth'])->group(function () {
    // Request form untuk mengakses informasi kota/TA
    Route::get('/laporan-ta/{id}/request', [App\Http\Controllers\KatalogController::class, 'showRequestForm'])
        ->name('katalog.request-form');

    // Send request untuk mengakses informasi kota/TA
    Route::post('/laporan-ta/{id}/request', [App\Http\Controllers\KatalogController::class, 'sendRequest'])
        ->name('katalog.send-request');
});

Route::get('/laporan-ta', [App\Http\Controllers\DetailKatalogController::class, 'index'])->name('laporan.index');
Route::get('/laporan-ta/{id_kota}', [App\Http\Controllers\DetailKatalogController::class, 'show'])->name('laporan.show');
// Route khusus untuk teks submission
Route::put('/submissions/teks/{artefak_id}', [App\Http\Controllers\SubmissionController::class, 'updateTeks'])->name('submissions.updateTeks');
Route::get('/katalog', [App\Http\Controllers\KatalogController::class, 'index'])->middleware(['auth', 'role:1,2,3'])->name('katalog');

Route::get('/storage/submissions/{filename}', function ($filename) {
    $path = storage_path('app/public/submissions' . $filename);

    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan.');
    }

    return response()->download($path);
})->name('download.pengesahan');
