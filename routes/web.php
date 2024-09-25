<?php

use Illuminate\Support\Facades\Route;


Route::get('/', \App\Livewire\Main::class);

Route::get('/{topic:slug}', \App\Livewire\StartSurvey::class);

Route::get('/{topic:slug}/{quiz:uuid}', \App\Livewire\SurveyQuestion::class);
