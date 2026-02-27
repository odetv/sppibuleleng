<?php

use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/api-wilayah/{path}', function ($path) {
    $url = "https://wilayah.id/api/" . $path;
    $response = Http::get($url);
    return $response->json();
})->where('path', '.*');

Route::get('/api-map-search', function (Illuminate\Http\Request $request) {
    $query = $request->query('q');
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($query) . "&limit=1";
    return Http::withHeaders([
        'User-Agent' => 'Laravel-App'
    ])->get($url)->json();
});

// 1. Landing Page
Route::get('/', function () {
    return view('landingpage');
});

// 3. Grup Middleware AUTH (Wajib Login)
Route::middleware('auth')->group(function () {

    // --- AREA 1: Route yang BOLEH diakses meski profil BELUM LENGKAP ---
    // User harus bisa akses ini untuk melengkapi datanya
    Route::get('/complete-profile', [PersonController::class, 'create'])->name('profile.complete');
    Route::post('/complete-profile', [PersonController::class, 'store'])->name('profile.store');

    // --- AREA 2: Route yang WAJIB PROFIL LENGKAP ---
    // Masukkan semua route yang butuh id_person ke dalam grup ini
    Route::middleware('profile.completed')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // --- MENU PENGATURAN (Profil) ---
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // --- MENU SPPG (Hanya KaSPPG, Ahli Gizi, Akuntansi) ---
        Route::middleware(['auth', 'position:kasppg,ag,ak'])->prefix('sppg')->name('sppg.')->group(function () {
            Route::get('/yayasan', function () {
                return "Halaman: Yayasan";
            })->name('yayasan');
            Route::get('/pks', function () {
                return "Halaman: Kerjasama (PKS)";
            })->name('pks');
            Route::get('/sertifikasi-sppg', function () {
                return "Halaman: Sertifikasi SPPG";
            })->name('sertifikasi');
            Route::get('/petugas-sppg', function () {
                return "Halaman: Petugas SPPG";
            })->name('petugas');
            Route::get('/kelompok-pm', function () {
                return "Halaman: Kelompok PM";
            })->name('pm');
            Route::get('/supplier-mbg', function () {
                return "Halaman: Supplier MBG";
            })->name('supplier');
        });
    });
});

// 4. Grup Middleware ADMINISTRATOR
Route::middleware(['auth', 'role:administrator', 'profile.completed'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/settings', function () {
        return view('admin.settings.index');
    })->name('settings.index');

    Route::post('/maintenance/toggle', [SettingController::class, 'toggleMaintenance'])
        ->name('maintenance.toggle');
        
    // Manajemen Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/approve-all', [UserController::class, 'approveAll'])->name('users.approve-all');
    Route::patch('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::delete('/users/trash/clear', [UserController::class, 'forceDeleteAll'])->name('users.force-delete-all');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/check-availability', [UserController::class, 'checkAvailability']);
    Route::post('/users/export', [UserController::class, 'exportExcel'])->name('users.export');
    Route::get('/users/template', [UserController::class, 'downloadTemplate'])->name('users.template');
    Route::post('/users/import', [UserController::class, 'importUsers'])->name('users.import');

    // Manajemen Role
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::patch('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');

    // Manajemen Jabatan
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::patch('/positions/{id}', [PositionController::class, 'update'])->name('positions.update');

    // Manajemen SPPG (Level Admin)
    Route::prefix('manage-sppg')->name('sppg.')->group(function () {
        // Route CRUD Utama
        Route::get('/list', [App\Http\Controllers\Admin\SppgUnitController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\SppgUnitController::class, 'create'])->name('create');
        Route::post('/store', [App\Http\Controllers\Admin\SppgUnitController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\Admin\SppgUnitController::class, 'edit'])->name('edit');
        Route::patch('/{id}/update', [App\Http\Controllers\Admin\SppgUnitController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Admin\SppgUnitController::class, 'destroy'])->name('destroy');
        Route::post('/export', [App\Http\Controllers\Admin\SppgUnitController::class, 'exportExcel'])->name('export');
        Route::get('/template', [App\Http\Controllers\Admin\SppgUnitController::class, 'downloadTemplate'])->name('template');
        Route::get('/check-availability', [App\Http\Controllers\Admin\SppgUnitController::class, 'checkAvailability'])->name('check-availability');
        Route::post('/import', [App\Http\Controllers\Admin\SppgUnitController::class, 'importSppg'])->name('import');

        // Route lainnya tetap dummy atau sesuaikan nanti
        Route::get('/pm', function () {
            return "Halaman: Daftar PM";
        })->name('pm');
        Route::get('/supplier', function () {
            return "Halaman: Daftar Supplier";
        })->name('supplier');
    });
});

require __DIR__ . '/auth.php';
