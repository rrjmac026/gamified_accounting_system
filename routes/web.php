<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\SubjectController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/student', [AdminController::class, 'students'])->name('student.index');
    // Route::get('/instructors', [AdminController::class, 'instructors'])->name('instructors.index');
    // Route::get('/subjects', [AdminController::class, 'subjects'])->name('subjects.index');
    Route::resource('/subjects',SubjectController::class);
    Route::resource('/instructors', InstructorController::class);
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});

Route::middleware(['auth', 'role:instructor'])->prefix('instructors')->name('instructors.')->group(function () {
    Route::get('/dashboard', [InstructorController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth', 'role:student'])->prefix('students')->name('students.')->group(function () {
        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
        Route::get('/progress', [StudentController::class, 'viewProgress'])->name('progress');
        Route::get('/assignments', [StudentController::class, 'viewAssignments'])->name('assignments');
        Route::get('/xp', [StudentController::class, 'viewXp'])->name('xp');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'show'])->name('login');
    Route::post('login', [LoginController::class, 'authenticate']);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');



//backup button para sa admin
// Route::post('/admin/backup-now', [DataBackupController::class, 'backupNow'])
//     ->name('admin.backup.now')
//     ->middleware(['auth', 'is_admin']);

//backup button para sa admin
// Route::post('/admin/backup-now', [DataBackupController::class, 'backupNow'])
//     ->name('admin.backup.now')
//     ->middleware(['auth', 'is_admin']);


