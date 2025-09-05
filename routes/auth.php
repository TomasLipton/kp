<?php

use App\Http\Controllers\Auth\Google;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register_v2')
        ->name('register');

    Volt::route('login', 'pages.auth.login_v2')
        ->name('login');
    Volt::route('login_old', 'pages.auth.login')
        ->name('login_old');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');

    Route::get('/auth/redirect/google', function () {
        return Socialite::driver('google')->redirect();
    })->name('auth.google.redirect');

    Route::get('/auth/callback/google', [Google::class, 'callback'])->name('auth.google.callback');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
