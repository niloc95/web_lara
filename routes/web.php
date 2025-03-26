<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Public routes
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'phpVersion' => PHP_VERSION,
    ]);
});

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Calendar routes
    Route::prefix('calendar')->middleware(['auth'])->group(function () {
        Route::get('/', [CalendarController::class, 'index'])->name('calendar');
        Route::get('/day/{date?}', [CalendarController::class, 'day'])->name('calendar.day');
        Route::get('/week/{date?}', [CalendarController::class, 'week'])->name('calendar.week');
        Route::get('/month/{date?}', [CalendarController::class, 'month'])->name('calendar.month');
    });

    // Appointments
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.status');

    // Clients
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/{name?}', [ClientController::class, 'show'])->name('clients.show');

    // Services
    Route::resource('services', ServiceController::class);

    // User profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Settings
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'update'])
        ->name('settings.update');

    // API Clients
    Route::post('/api/clients', [App\Http\Controllers\ClientApiController::class, 'store'])
        ->name('clients.api.store');

    // Settings routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
        Route::post('/', [App\Http\Controllers\SettingsController::class, 'update'])->name('settings.update');
        Route::post('/hours', [App\Http\Controllers\SettingsController::class, 'updateBusinessHours'])->name('settings.update.hours');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/general', [App\Http\Controllers\SettingsController::class, 'updateGeneral'])->name('settings.update.general');
    Route::post('/settings/hours', [App\Http\Controllers\SettingsController::class, 'updateBusinessHours'])->name('settings.update.hours');
    Route::post('/settings/logo', [App\Http\Controllers\SettingsController::class, 'updateLogo'])->name('settings.update.logo');
});

require __DIR__.'/auth.php';