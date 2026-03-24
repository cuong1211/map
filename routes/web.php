<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/map', [HomeController::class, 'map'])->name('map');
Route::get('/api/locations', [HomeController::class, 'apiLocations'])->name('api.locations');

// Admin auth routes
Route::get('/admin/login', [Admin\AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [Admin\AuthController::class, 'login']);
Route::post('/admin/logout', [Admin\AuthController::class, 'logout'])->name('admin.logout');

// Admin protected routes
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/', fn() => redirect()->route('admin.locations.index'));
    Route::resource('locations', Admin\LocationController::class)->names('admin.locations');
    Route::resource('categories', Admin\CategoryController::class)->names('admin.categories');
});
