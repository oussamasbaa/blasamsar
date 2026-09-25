<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Chat Widget Routes
Route::get('/chat/history', [\App\Http\Controllers\ChatController::class, 'history'])->name('chat.history');
Route::post('/chat/start', [\App\Http\Controllers\ChatController::class, 'start'])->name('chat.start');
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
Route::post('/chat/clear', [\App\Http\Controllers\ChatController::class, 'clear'])->name('chat.clear');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Car Reservations & Purchasing
    Route::get('/cars/{car}/buy', [\App\Http\Controllers\CarController::class, 'buy'])->name('cars.buy');
    Route::post('/cars/{car}/reserve', [\App\Http\Controllers\ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/my-reservations', [\App\Http\Controllers\ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}/facture', [\App\Http\Controllers\ReservationController::class, 'facture'])->name('reservations.facture');

    // Car Comparisons
    Route::post('/compare/add/{car}', [\App\Http\Controllers\ComparisonController::class, 'add'])->name('compare.add');
    Route::get('/compare', [\App\Http\Controllers\ComparisonController::class, 'index'])->name('compare.index');
});

// Public Car Browsing
Route::get('/cars', [\App\Http\Controllers\CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car}', [\App\Http\Controllers\CarController::class, 'show'])->name('cars.show');
Route::get('/cars/{car}/fiche', [\App\Http\Controllers\CarController::class, 'fiche'])->name('cars.fiche');

// Car Configurator
Route::get('/configurateur', [\App\Http\Controllers\CarController::class, 'configurator'])->name('cars.configurator');
Route::get('/api/models', [\App\Http\Controllers\CarController::class, 'getModels'])->name('api.models');
Route::get('/api/colors', [\App\Http\Controllers\CarController::class, 'getColors'])->name('api.colors');

// Smart Attendance System
Route::get('/attendance/scan', [\App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/attendance/record', [\App\Http\Controllers\AttendanceController::class, 'record'])->name('attendance.record');

// Admin Routes (replaced by Filament)
// Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
//     Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
//     Route::resource('cars', \App\Http\Controllers\Admin\CarController::class);
//     Route::get('/reservations', [\App\Http\Controllers\Admin\ReservationController::class, 'index'])->name('reservations.index');
//     Route::patch('/reservations/{reservation}/status', [\App\Http\Controllers\Admin\ReservationController::class, 'updateStatus'])->name('reservations.updateStatus');
//     Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
// });

require __DIR__.'/auth.php';
