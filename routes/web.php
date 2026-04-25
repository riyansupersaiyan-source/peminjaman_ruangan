<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// Controller Utama
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookingController;

// Controller Khusus Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\ActivityTypeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingManagementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 🚀 TEST KONEKSI MONGODB
Route::get('/test-db', function () {
    try {
        DB::connection('mongodb')->getMongoClient();
        return "Koneksi BERHASIL";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

// Rute Halaman Depan
Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rute yang Membutuhkan Login
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // === ADMIN ===
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('rooms', RoomController::class);
        Route::resource('activity-types', ActivityTypeController::class);
        Route::resource('users', UserController::class);

        Route::get('bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
        Route::patch('bookings/{booking}/approve', [BookingManagementController::class, 'approve'])->name('bookings.approve');
        Route::patch('bookings/{booking}/reject', [BookingManagementController::class, 'reject'])->name('bookings.reject');
    });

    // === DOSEN & MAHASISWA ===
    Route::middleware('role:dosen,mahasiswa')->group(function () {
        
        Route::get('bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('bookings', [BookingController::class, 'store'])->name('bookings.store');

        Route::get('my-bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('my-bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');

        Route::patch('my-bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    });
});

// Auth Breeze
require __DIR__.'/auth.php';