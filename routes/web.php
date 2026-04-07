<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\SubmissionController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ExportController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if(auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// ==========================================
// Admin Routes - Protected by admin middleware
// ==========================================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])
            ->name('users.show');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->name('users.destroy');

        // Forms
        Route::resource('forms', FormController::class);
        // Submissions
        Route::get('/submissions', [SubmissionController::class, 'index'])
            ->name('submissions.index');
        Route::get('/submissions/{submission}', [SubmissionController::class, 'show'])
            ->name('submissions.show');
        Route::delete('/submissions/{submission}', [SubmissionController::class, 'destroy'])
            ->name('submissions.destroy');

        Route::get('/import', [ImportController::class, 'index'])
            ->name('import.index');
        Route::post('/import/preview', [ImportController::class, 'preview'])
            ->name('import.preview');
        Route::post('/import/confirm', [ImportController::class, 'confirm'])
            ->name('import.confirm');
        Route::get('/import/sample', [ImportController::class, 'sampleCsv'])
            ->name('import.sample');
        // Export
        Route::get('/export', [ExportController::class, 'index'])
            ->name('export.index');
        Route::post('/export', [ExportController::class, 'export'])
            ->name('export.csv');
    });


require __DIR__.'/auth.php';
