<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\AnalyticsController;

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
    Route::get('/get-students', [AbsenceController::class, 'getStudents'])->name('get.students');
    Route::resource('absences', AbsenceController::class)->except(['create', 'store', 'show']);
    Route::get('/absences/bulk-create', [AbsenceController::class, 'bulkCreate'])->name('absences.bulk-create');
    Route::post('/absences/store-bulk', [AbsenceController::class, 'storeBulk'])->name('absences.store-bulk');
    Route::get('/absences/get-class-data', [AbsenceController::class, 'getClassData'])->name('absences.get-class-data');
    
    // Analytics routes
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/analytics/{class}', [AnalyticsController::class, 'show'])->name('analytics.show');
    Route::put('/analytics/{class}', [AnalyticsController::class, 'updateAnalytics'])->name('analytics.update');
});
