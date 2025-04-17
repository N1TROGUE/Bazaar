<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Auth routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home route
Route::get('/index', function () {
    return view('index');
})->name('index')->middleware('auth');

// Verhuur route
Route::get('/rental', function () {
    return view('rental');
})->name('rental');

// Verhuur route
Route::get('/contracten', function () {
    return view('contracten');
})->name('contracten');


// Root route: redirect to index if authenticated, otherwise to login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('index') : redirect()->route('show.login');
});
