<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('scan/{location:qr_token}')->name('scan.')->group(function () {
    Route::get('/', [ScanController::class, 'show'])->name('show');
    Route::post('/login', [ScanController::class, 'login'])->name('login')->middleware('throttle:20,1');
    Route::post('/logout', [ScanController::class, 'logout'])->name('logout');
    Route::post('/time-in', [ScanController::class, 'timeIn'])->name('time-in')->middleware('throttle:30,1');
    Route::post('/time-out', [ScanController::class, 'timeOut'])->name('time-out')->middleware('throttle:30,1');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
