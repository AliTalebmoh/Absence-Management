<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AbsenceController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Classes Routes
    Route::resource('classes', ClassController::class);
    
    // Students Routes
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/analytics', [StudentController::class, 'analytics'])->name('students.analytics');
    
    // Absences Routes
    Route::resource('absences', AbsenceController::class);
    Route::get('get-students', [AbsenceController::class, 'getStudents'])->name('get.students');
    Route::get('absences/quick-record/{student}', [AbsenceController::class, 'quickRecord'])->name('absences.quick-record');
});
