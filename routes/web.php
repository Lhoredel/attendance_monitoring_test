<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\UserController;

// Login
Route::get('/', [LoginController::class, 'showLogin'])
    ->name('login');

Route::get('/login', [LoginController::class, 'showLogin']);

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.post');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Authenticated routes
Route::middleware('auth')->group(function () {

    // Admin routes
    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [
                DashboardController::class,
                'admin'
            ])->name('dashboard');

            Route::resource('students', StudentController::class);

            Route::resource('classes', SchoolClassController::class);

            Route::post('/classes/{class}/students', [
                SchoolClassController::class,
                'addStudent'
            ])->name('classes.students.add');

            Route::delete('/classes/{class}/students/{student}', [
                SchoolClassController::class,
                'removeStudent'
            ])->name('classes.students.remove');

            Route::get('/attendance', [
                AttendanceController::class,
                'index'
            ])->name('attendance.index');

            Route::post('/attendance', [
                AttendanceController::class,
                'store'
            ])->name('attendance.store');

            Route::get('/reports', [
                AttendanceController::class,
                'reports'
            ])->name('reports');
        });

    // Teacher routes
    Route::middleware('role:teacher')
        ->prefix('teacher')
        ->name('teacher.')
        ->group(function () {

            Route::get('/dashboard', [
                DashboardController::class,
                'teacher'
            ])->name('dashboard');

            Route::get('/attendance', [
                AttendanceController::class,
                'index'
            ])->name('attendance.index');

            Route::post('/attendance', [
                AttendanceController::class,
                'store'
            ])->name('attendance.store');

            Route::get('/reports', [
                AttendanceController::class,
                'reports'
            ])->name('reports');
        });

        });

    // User management routes (accessible by both Admin and Teacher)
    Route::middleware('role:admin,teacher')
        ->prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('index');
            Route::get('/create', [UserController::class, 'create'])
                ->name('create');
            Route::post('/', [UserController::class, 'store'])
                ->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])
                ->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])
                ->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->name('destroy');

        });

    Route::middleware('role:admin,teacher')->group(function () {
    Route::resource('users', UserController::class)
        ->except(['show']);
});

