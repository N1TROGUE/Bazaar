<?php

use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyLandingPageController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\FavoriteAdvertisementController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserReviewController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAdminOrBusiness;
use App\Http\Middleware\CheckAdvertiser;
use App\Http\Middleware\CheckBusinessAdvertiser;
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
// Advertisements routes
Route::resource('advertisements', AdvertisementController::class)->middleware('auth');

// Advertisement review route
Route::post('/advertisements/{advertisement}/review', [ReviewController::class, 'store'])->name('advertisements.review')->middleware('auth');

// User review route
Route::get('/my-orders/{order}/review-seller', [UserReviewController::class, 'show'])->name('user.review')->middleware('auth');

// Alleen toegankelijk voor admins
Route::middleware([CheckAdmin::class])->group(function () {
    Route::get('/upload-contract', [ContractController::class, 'showContract'])->name('upload.contract');
    Route::get('/exporteer-contract', [ContractController::class, 'exportContract'])->name('export.registration');
    Route::post('/contracts', [ContractController::class, 'storeContract'])->name('contracts.store');
    Route::get('/contracts/export/{user}', [ContractController::class, 'downloadContractPdf'])->name('contracts.export.pdf');
    Route::get('/thema-instellingen', [SettingsController::class, 'showSettings'])->name('settings.show');
    Route::post('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
});

// Alleen toegankelijk voor zakelijke adverteerders
Route::middleware([CheckAdminOrBusiness::class])->group(function () {
    Route::get('/thema-instellingen', [SettingsController::class, 'showSettings'])->name('settings.show');
    Route::post('/settings', [SettingsController::class, 'updateSettings'])->name('settings.update');
});

// Alleen toegankelijk voor adverteerders (particulier of zakelijk)
Route::middleware([CheckAdvertiser::class])->group(function () {
    Route::get('/advertenties/nieuw', [AdvertisementController::class, 'create'])->name('advertisements.create');
    Route::get('/mijn-advertenties', [AdvertisementController::class, 'showAdvertisements'])->name('advertisements.my');
    Route::get('/mijn-verhuringen', [RentalController::class, 'showRentals'])->name('rentals.show');
    Route::post('/advertenties', [AdvertisementController::class, 'store'])->name('advertisements.store');
});

// Root route: redirect to index if authenticated, otherwise to login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('index') : redirect()->route('show.login');
});

// Rental routes
Route::middleware('auth')->controller(RentalController::class)->group(function () {
    Route::get('/advertisements/{advertisement}/rent', 'create')->name('advertisements.rent');
    Route::get('/gehuurde-producten', 'showRented')->name('rented.show');
    Route::post('/advertisements/{advertisement}/rent', 'store')->name('advertisements.rent.store');
    Route::get('/gehuurde-producten/{rental}/confirm-return', 'confirmReturn')->name('rentals.confirmReturn');
    Route::post('/gehuurde-producten/{rental}/return', 'returnRental')->name('rentals.return');
});

Route::middleware('auth')->controller(OrderController::class)->group(function () {
   Route::get('/advertisements/{advertisement}/order', 'create')->name('order.create');
   Route::post('/advertisements/{advertisement}/order', 'store')->name('order.store');
   Route::get('/my-orders', 'index')->name('orders.index');
});

// Company landing page route
Route::get('/company/{slug}', [CompanyLandingPageController::class, 'show'])->name('company.landing')->middleware('auth'); // Public route for the company landing page

Route::post('/advertisements/{advertisement}/favorite', [FavoriteAdvertisementController::class, 'toggle'])->name('advertisements.favorite');
