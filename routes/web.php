<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VideoBundleController;
use App\Http\Controllers\UserController; // Importeer de UserController
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Openbare route voor de welkomstpagina (Public welcome page route)
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');

// Beveiligde routes (Authenticated routes)
Route::middleware(['auth'])->group(function () {
    // Video-bundels route (Route for video bundles)
    Route::get('/video-bundles', [VideoBundleController::class, 'index'])->name('video-bundles.index');

    // Dashboard route (Only accessible to verified users)
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Profielbeheer routes (Profile management routes)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Uitloggen route (Logout route)
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Route voor nieuwe gebruiker (Add user route)
Route::post('/add-user', [UserController::class, 'addUser'])->name('add-user');

// Routes voor authenticatie (Authentication routes)
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

require __DIR__ . '/auth.php';
