<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PresencesController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// login
Route::get('/loginauth', [TestingController::class, 'index'])->name('loginauth');
Route::post('/loginauth', [App\Http\Controllers\TestingController::class, 'Login'])->name('login.post');
Route::post('/logout', [App\Http\Controllers\TestingController::class, 'logout'])->name('logout');
//role views
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    //routing untuk kelender admin
    Route::get('/admin/absensi/detail/{tanggal}', [AdminController::class, 'detailAbsensiTanggal'])->name('admin.absensi.detail');
    //routing untuk admin
    Route::get('admin/present', [AdminController::class, 'showListPegawai'])->name('pegawai_present');
    route::get('admin/edit{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('admin/update{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::get('admin/delete{id}', [AdminController::class, 'destroy'])->name('admin.delete');
    //show absensi seluruh pegawai hari ini
    Route::get('admin/presence', [AdminController::class, 'showAbsensiPegawaiToday'])->name('admin.presence');
    Route::get('admin/exportoexcel', [AdminController::class, 'exportExcel'])->name('admin.excel');
    //rekap absensi bulanan
    Route::get('admin/presence/rekap-bulanan', [PresencesController::class, 'rekapBulanan'])->name('admin.presence.rekapBulanan');
    Route::get('admin/presence/rekap-bulanan/excel-bulanan', [PresencesController::class, 'exporExcelBulanan'])->name('admin.presence.exportBulanan');
    //user management
    Route::get('/usermanagement', [UserController::class, 'index'])->name('usermanagement.index');
    Route::get('/editusermanagement{id}', [UserController::class, 'setUser'])->name('usermanagement.edit');
    Route::post('/storeusermanagement{id}', [UserController::class, 'storeUser'])->name('usermanagement.store');
    Route::delete('/deleteusermanagement/{id}', [UserController::class, 'destroyUser'])->name('usermanagement.delete');
    Route::resource('admin', AdminController::class);
});
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/presences', [PresencesController::class, 'index'])->name('pages.presence');
    // Route::resource('presences', PresencesController::class);
    //routing untuk absensi
    Route::get('absensi/absen', [PresencesController::class, 'cekAbsensi'])->name('absensi.index');
    Route::post('absensi/absen', [\App\Http\Controllers\ProfileController::class, 'storeAbsen'])->name('absensi.store');
    //routing untuk absen pulang
    Route::post('absensi/pulang', [PresencesController::class, 'absenPulang'])->name('absensi.pulang');
});
