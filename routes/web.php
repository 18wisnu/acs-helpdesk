<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// Halaman Welcome
Route::get('/', function () {
    return view('welcome');
});

// Route darurat untuk jalankan migrasi via URL
Route::get('/run-migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return Artisan::output();
});

// ------------------------------------------------------------------
// Rute yang butuh login (auth)
// ------------------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard – list semua device
    Route::get('/dashboard', [HelpdeskController::class, 'index'])->name('dashboard');

    // --- FITUR HELPDESK & GENIEACS ---
    Route::prefix('helpdesk')->name('helpdesk.')->group(function () {
        // Sync device dari GenieACS
        Route::post('/sync-devices', [HelpdeskController::class, 'syncDevices'])->name('sync');
        Route::post('/cleanup-discovery', [HelpdeskController::class, 'cleanupDiscovery'])->name('cleanup-discovery');
        
        // Fitur Wi-Fi & PPPoE
        Route::post('/update-wifi', [HelpdeskController::class, 'updateWifi'])->name('update-wifi');
        Route::post('/update-pppoe', [HelpdeskController::class, 'updatePppoe'])->name('update-pppoe');
        
        // Pendaftaran Pelanggan (Fitur Client Portal Baru)
        Route::post('/register-customer', [HelpdeskController::class, 'registerCustomer'])->name('register-customer');
        
        // Map Pelanggan
        Route::get('/map', [\App\Http\Controllers\MapController::class, 'index'])->name('map');
        
        // Fitur Diagnostik
        Route::match(['get', 'post'], '/device/{id}/diagnostic', [HelpdeskController::class, 'diagnostic'])->name('diagnostic');
        
        // Detail & Reboot modem
        Route::get('/device/{id}', [HelpdeskController::class, 'show'])->name('detail');
        Route::post('/device/{id}/reboot', [HelpdeskController::class, 'reboot'])->name('reboot');
        
        // Update Site pada Device
        Route::post('/update-site', [HelpdeskController::class, 'updateSite'])->name('update-site');

        // Manajemen User (Admin & Staff)
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::put('/{id}', [UserController::class, 'update'])->name('update');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        });
    });

// --- FITUR MANAJEMEN ODP (SPLITTER) ---
Route::prefix('odps')->name('helpdesk.odps.')->group(function () {
    Route::get('/', [App\Http\Controllers\OdpController::class, 'index'])->name('index');
    Route::post('/store', [App\Http\Controllers\OdpController::class, 'store'])->name('store');
    Route::put('/update/{id}', [App\Http\Controllers\OdpController::class, 'update'])->name('update');
    Route::delete('/destroy/{id}', [App\Http\Controllers\OdpController::class, 'destroy'])->name('destroy');
});

// --- FITUR MANAJEMEN SITE (OLT/LOKASI) ---
Route::prefix('sites')->name('sites.')->group(function () {
    Route::get('/', [SiteController::class, 'index'])->name('index');       // Tampil data
    Route::post('/store', [SiteController::class, 'store'])->name('store'); // Simpan baru
    Route::put('/update/{id}', [SiteController::class, 'update'])->name('update'); // Edit data
    Route::delete('/destroy/{id}', [SiteController::class, 'destroy'])->name('destroy'); // Hapus data
});

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';