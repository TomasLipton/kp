<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $topics = \App\Models\Topics::all();

    return view('bg', ['topics' => $topics]);
});

