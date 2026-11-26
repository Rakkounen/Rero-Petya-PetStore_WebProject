<?php

use App\Http\Controllers\Admin\BoardingRoomController as AdminBoardingRoomController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\BoardingController;
use App\Http\Controllers\GroomingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('services.index');
});

Route::get('/penitipan', [BoardingController::class, 'index'])->name('boarding.index');
Route::get('/grooming', [GroomingController::class, 'index'])->name('grooming.index');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/layanan/penitipan/{boardingRoom:code}', [ServiceController::class, 'showBoardingRoom'])
    ->name('services.boarding.show');
Route::get('/layanan/{service}', [ServiceController::class, 'show'])->name('services.show');

Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
Route::post('/login', [AdminLoginController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('login.store');
Route::redirect('/admin/login', '/login');
Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('register.store');
Route::post('/admin/logout', [AdminLoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function (): void {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::resource('boarding-rooms', AdminBoardingRoomController::class)->except('show');
        Route::resource('services', AdminServiceController::class)->except('show');
        Route::resource('products', AdminProductController::class)->except('show');
    });
