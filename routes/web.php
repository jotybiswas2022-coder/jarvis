<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\JarvisController;

// ========== JARVIS ROUTES ==========
Route::controller(JarvisController::class)->group(function () {
    Route::get('/', 'index')->name('jarvis.home');
    Route::get('/chat', 'chatPage')->name('jarvis.chat');
    Route::post('/api/chat', 'chat')->name('jarvis.chat');
    Route::post('/api/weather', 'weather')->name('jarvis.weather');
    Route::get('/api/system-info', 'systemInfo')->name('jarvis.system');
    Route::post('/api/search', 'search')->name('jarvis.search');
    Route::post('/api/open-app', 'openApp')->name('jarvis.openapp');
});

// Site contact page routes
Route::controller(SiteController::class)->group(function () {
    Route::get('/contact', 'contact')->name('contact.page');
});

// Password reset link request form route
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');


// Authentication routes
Auth::routes();

// Include admin route file
include('admin.php');
