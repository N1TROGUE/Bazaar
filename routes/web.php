<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAdvertiser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Sabberworm\CSS\Settings;

// Auth routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home route
Route::get('/index', [AdvertisementController::class, 'index'])->name('index')->middleware('auth');

// Alleen toegankelijk voor admin
Route::middleware([CheckAdmin::class])->group(function () {
    Route::get('/upload-contract', [ContractController::class, 'showContract'])->name('upload.contract');
    Route::get('/exporteer-contract', [ContractController::class, 'exportContract'])->name('export.registration');
    Route::post('/contracts', [ContractController::class, 'storeContract'])->name('contracts.store');
    Route::get('/contracts/export/{user}', [ContractController::class, 'downloadContractPdf'])->name('contracts.export.pdf');
    Route::get('/thema-instellingen', [SettingsController::class, 'showSettings'])->name('settings.show');
    Route::post('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
});

// Alleen toegankelijk voor adverteerders (particulier of zakelijk)
Route::middleware([CheckAdvertiser::class])->group(function () {
    Route::get('/advertenties/nieuw', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::get('/mijn-advertenties', [AdvertisementController::class, 'showAdvertisements'])->name('advertisements.show');
    Route::post('/advertenties', [AdvertisementController::class, 'store'])->name('advertisements.store');
});

// Root route: redirect to index if authenticated, otherwise to login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('index') : redirect()->route('show.login');
});
