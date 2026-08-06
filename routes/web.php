<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('operations.dashboard');
});

// Admin Dashboards (Original URLs preserved to fix broken navigation)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/operations-dashboard', [\App\Http\Controllers\OperationsController::class, 'dashboard'])
        ->name('operations.dashboard');
        
    Route::get('/operations-dashboard/workforce-attendance', [\App\Http\Controllers\OperationsController::class, 'attendances'])
        ->middleware('role:admin,supervisor,hrd')
        ->name('workforce.attendance');

    Route::get('/operations-dashboard/workforce-attendance/employee/{employee_id}', [\App\Http\Controllers\OperationsController::class, 'employeeHistory'])
        ->middleware('role:admin,supervisor,hrd')
        ->name('workforce.attendance.employee');
        
    Route::get('/operations-dashboard/fleet-management', [\App\Http\Controllers\OperationsController::class, 'units'])
        ->middleware('role:admin,supervisor,workshop')
        ->name('fleet.management');

    // Workforce Employee Management (CRUD - Restricted to Admin & HRD)
    Route::get('/operations-dashboard/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])
        ->middleware('role:admin,hrd')
        ->name('workforce.employees');
    Route::post('/operations-dashboard/employees', [\App\Http\Controllers\EmployeeController::class, 'store'])
        ->middleware('role:admin,hrd')
        ->name('employees.store');
    Route::put('/operations-dashboard/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'update'])
        ->middleware('role:admin,hrd')
        ->name('employees.update');
    Route::delete('/operations-dashboard/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'destroy'])
        ->middleware('role:admin,hrd')
        ->name('employees.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

use App\Http\Controllers\AttendanceController;

Route::get('/attendance', [AttendanceController::class, 'create'])->name('attendance.create');
Route::get('/attendance/check-status', [AttendanceController::class, 'checkStatus'])->middleware('throttle:30,1')->name('attendance.check-status');
Route::post('/attendance', [AttendanceController::class, 'store'])->middleware('throttle:attendance')->name('attendance.store');
Route::get('/attendance/success', function () {
    return view('forms.attendance-success');
})->name('attendance.success');

Route::get('/fleet/success', function () {
    return view('forms.fleet-success');
})->name('fleet.success');
Route::get('/fleet/{qr_code?}', [\App\Http\Controllers\FleetController::class, 'create'])->name('fleet.create');
Route::post('/fleet', [\App\Http\Controllers\FleetController::class, 'store'])->middleware('throttle:10,1')->name('fleet.store');

require __DIR__ . '/auth.php';
