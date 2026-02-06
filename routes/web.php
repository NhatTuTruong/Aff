<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Redirect login route to Filament admin login
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

// Landing Pages
Route::get('/review/{slug}', [App\Http\Controllers\LandingPageController::class, 'show'])
    ->name('landing.show');

// Click Tracking & Redirect
Route::get('/out/{slug}', [App\Http\Controllers\ClickTrackingController::class, 'redirect'])
    ->name('click.redirect');

// Legal Pages
Route::get('/about', function () {
    return view('legal.about');
})->name('legal.about');

Route::get('/contact', function () {
    return view('legal.contact');
})->name('legal.contact');

Route::get('/privacy', function () {
    return view('legal.privacy');
})->name('legal.privacy');

