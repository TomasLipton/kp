<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $topics = \App\Models\Topics::all();

    return view('bg', ['topics' => $topics]);
});


Route::get('/{topic:slug}', \App\Livewire\StartSurvey::class);

Route::get('/{topic:slug}/{quiz:uuid}', \App\Livewire\SurveyQuestion::class);
