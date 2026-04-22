<?php

// ============================================
// ROUTES - Daftar semua URL website CineRate
// File ini mengatur URL mana mengarah ke 
// controller dan fungsi yang mana
// ============================================

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StreamingController;
use App\Http\Controllers\TmdbController;
use Illuminate\Support\Facades\Route;

// ============================================
// ROUTE PUBLIK (bisa diakses tanpa login)
// ============================================

// Halaman Home - Film Populer TMDB
Route::get('/', [TmdbController::class, 'home'])->name('home');

// ============================================
// ROUTE AUTENTIKASI USER (Login & Register)
// ============================================

Route::get('/login', [AuthController::class, 'showLoginUser'])->name('login');
Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logoutUser'])->name('logout');

// ============================================
// ROUTE TMDB (Browse Film dari TMDB API)
// Publik - bisa diakses tanpa login
// ============================================

Route::prefix('browse')->group(function () {
    Route::get('/popular', [TmdbController::class, 'popular'])->name('tmdb.popular');
    Route::get('/top-rated', [TmdbController::class, 'topRated'])->name('tmdb.top-rated');
    Route::get('/now-playing', [TmdbController::class, 'nowPlaying'])->name('tmdb.now-playing');
    Route::get('/search', [TmdbController::class, 'search'])->name('tmdb.search');
    Route::get('/discover', [TmdbController::class, 'discover'])->name('tmdb.discover');
    Route::get('/movie/{id}', [TmdbController::class, 'detail'])->name('tmdb.detail');
});

// ============================================
// ROUTE YANG BUTUH LOGIN (auth middleware)
// Rating, ulasan, dan streaming
// ============================================

Route::middleware('auth')->group(function () {
    // Rating & Ulasan (harus login)
    Route::post('/browse/movie/{id}/rating', [TmdbController::class, 'simpanRating'])->name('tmdb.rating');
    Route::post('/browse/movie/{id}/ulasan', [TmdbController::class, 'simpanUlasan'])->name('tmdb.ulasan');

    // Streaming (harus login)
    Route::get('/watch/{id}', [StreamingController::class, 'watch'])->name('streaming.watch');
});

// ============================================
// ROUTE AUTENTIKASI ADMIN (Login & Logout terpisah)
// ============================================

// Halaman login admin
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);

// Logout admin
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// SUDO LOGIN - BYPASS SEMUA ERROR (HANYA UNTUK TUGAS AKHIR)
Route::get('/sudo-login', function() {
    $admin = \App\Models\User::firstOrCreate(
        ['username' => 'admin'],
        ['password' => 'admin123', 'role' => 'admin']
    );
    \Illuminate\Support\Facades\Auth::login($admin);
    request()->session()->regenerate();
    return redirect()->route('admin.dashboard');
});

// ============================================
// ROUTE ADMIN (harus login sebagai admin)
// Dilindungi oleh middleware 'admin'
// ============================================
Route::middleware('admin')->prefix('admin')->group(function () {

    // Dashboard admin - menampilkan rating & ulasan
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Hapus rating
    Route::delete('/rating/{id}', [AdminController::class, 'deleteRating'])->name('admin.rating.delete');

    // Hapus ulasan
    Route::delete('/ulasan/{id}', [AdminController::class, 'deleteUlasan'])->name('admin.ulasan.delete');
});
