<?php

use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Landing Page
Route::get('/', function () {
    return view('landingpage');
});

// 2. Dashboard (Bisa diakses semua role yang login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'profile.completed'])->name('dashboard');

// 3. Grup Middleware AUTH (Wajib Login)
Route::middleware('auth')->group(function () {

    // --- MENU PENGATURAN (Profil) ---
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Alur Lengkapi Profil (Person)
    Route::get('/complete-profile', [PersonController::class, 'create'])->name('profile.complete');
    Route::post('/complete-profile', [PersonController::class, 'store'])->name('profile.store');

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

// 4. Grup Middleware ADMINISTRATOR
Route::middleware(['auth', 'role:administrator'])->prefix('admin')->name('admin.')->group(function () {

    // Manajemen Pengguna
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/approve-all', [UserController::class, 'approveAll'])->name('users.approve-all');
    Route::patch('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{id}/force', [UserController::class, 'forceDelete'])->name('users.force-delete');
    Route::delete('/users/trash/clear', [UserController::class, 'forceDeleteAll'])->name('users.force-delete-all');
    Route::post('/users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');

    // Manajemen Role
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::patch('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');

    // Manajemen Jabatan
    Route::get('/positions', [PositionController::class, 'index'])->name('positions.index');
    Route::patch('/positions/{id}', [PositionController::class, 'update'])->name('positions.update');

    // Manajemen SPPG (Level Admin)
    Route::prefix('manage-sppg')->name('sppg.')->group(function () {
        Route::get('/list', function () {
            return "Halaman Dummy Admin: Daftar SPPG";
        })->name('index');
        Route::get('/pm', function () {
            return "Halaman Dummy Admin: Daftar PM";
        })->name('pm');
        Route::get('/supplier', function () {
            return "Halaman Dummy Admin: Daftar Supplier MBG";
        })->name('supplier');
    });
});

require __DIR__ . '/auth.php';
