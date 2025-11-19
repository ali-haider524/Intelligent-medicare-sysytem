<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::get('/appointments-today', [AppointmentController::class, 'todayAppointments'])->name('appointments.today');
    Route::get('/api/available-slots', [AppointmentController::class, 'getAvailableSlots'])->name('api.available-slots');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
    });
});

require __DIR__.'/auth.php';