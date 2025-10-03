<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });

    Route::get('/', \App\Livewire\Main::class)->name('dashboard');

    Volt::route('topics', 'topics')->name('topics');

    Route::get('profile', \App\Livewire\Profile::class)
        ->middleware(['auth'])
        ->name('profile');

    Route::get('ai', \App\Livewire\AiPage::class)->name('ai');

    Volt::route('ai-quiz', 'ai-quiz')->name('ai-quiz');

    require __DIR__.'/auth.php';

    Route::get('/{topic:slug}', \App\Livewire\StartSurvey::class);
    Route::get('/{topic:slug}/{quiz:uuid}', \App\Livewire\SurveyQuestion::class);

});
