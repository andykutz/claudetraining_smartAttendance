<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::middleware('role:admin,manager')->group(function () {
        Route::resource('employees', EmployeeController::class)->except('show');
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('locations', LocationController::class);
        Route::get('locations/{location}/qr', [LocationController::class, 'qr'])->name('locations.qr');
        Route::post('locations/{location}/regenerate-token', [LocationController::class, 'regenerateToken'])->name('locations.regenerate-token');
        Route::resource('users', UserController::class)->except('show');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::get('settings/api-docs/pdf', [SettingsController::class, 'apiDocsPdf'])->name('settings.api-docs.pdf');
    });
});
