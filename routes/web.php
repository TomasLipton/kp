<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Main::class)->name('dashboard');

Route::get('profile', \App\Livewire\Profile::class)
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::get('/{topic:slug}', \App\Livewire\StartSurvey::class);
Route::get('/{topic:slug}/{quiz:uuid}', \App\Livewire\SurveyQuestion::class);
