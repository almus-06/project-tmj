<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/attendances', [\App\Http\Controllers\AdminController::class, 'attendances'])->name('admin.attendances');
    Route::get('/admin/units', [\App\Http\Controllers\AdminController::class, 'units'])->name('admin.units');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UnitStatusController;

Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
Route::get('/attendance/success', function () { return view('attendance.success'); })->name('attendance.success');

Route::get('/unit/status', [UnitStatusController::class, 'create'])->name('unit.status.create');
Route::post('/unit/status', [UnitStatusController::class, 'store'])->name('unit.status.store');
Route::get('/unit/status/success', function () { return view('unit_status.success'); })->name('unit.status.success');

require __DIR__.'/auth.php';
