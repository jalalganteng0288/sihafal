<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Santri;
use App\Http\Controllers\Ustadz;
use Illuminate\Support\Facades\Route;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Logout (auth required)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard placeholders — akan diisi di task berikutnya
Route::middleware('auth')->group(function () {
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Santri
        Route::resource('santri', Admin\SantriController::class)->except(['show', 'destroy']);
        Route::patch('santri/{santri}/deactivate', [Admin\SantriController::class, 'deactivate'])->name('santri.deactivate');

        // Manajemen Ustadz
        Route::resource('ustadz', Admin\UstadzController::class)->except(['show', 'destroy']);

        // Manajemen Halaqah
        Route::resource('halaqah', Admin\HalaqahController::class)->except(['show', 'destroy']);
    });

    Route::middleware('role:ustadz')->prefix('ustadz')->name('ustadz.')->group(function () {
        Route::get('/dashboard', [Ustadz\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('setoran', Ustadz\SetoranController::class)->except(['show', 'destroy']);
        Route::get('evaluasi', [Ustadz\EvaluasiController::class, 'index'])->name('evaluasi.index');
        Route::get('evaluasi/{setoran}', [Ustadz\EvaluasiController::class, 'show'])->name('evaluasi.show');
        Route::get('santri/{santri}/evaluasi', [Ustadz\EvaluasiController::class, 'riwayat'])->name('evaluasi.riwayat');

        Route::get('target', [Ustadz\TargetController::class, 'index'])->name('target.index');
        Route::post('target', [Ustadz\TargetController::class, 'store'])->name('target.store');
        Route::put('target/{target}', [Ustadz\TargetController::class, 'update'])->name('target.update');
    });

    Route::middleware('role:santri')->prefix('santri')->name('santri.')->group(function () {
        Route::get('/dashboard', [Santri\DashboardController::class, 'index'])->name('dashboard');
    });

    // Notifikasi — semua role
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.read-all');
    Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');

    // Laporan — admin dan ustadz
    Route::middleware('role:admin,ustadz')->group(function () {
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
        Route::post('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');
    });
});
