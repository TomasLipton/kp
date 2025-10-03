<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use OpenAI\Laravel\Facades\OpenAI;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('d', function () {
    $response = OpenAI::realtime()->token([
//    'session' => [
        'instructions' => 'You are a teacher'
//    ]
    ]);
dd($response->clientSecret->expiresAt);
dd($response->clientSecret->value);

})->purpose('Display an inspiring quote')->hourly();

Artisan::command('a', function () {})->purpose('Display an inspiring quote')->hourly();
