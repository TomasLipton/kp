<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('d', function () {
    \Carbon\Carbon::setLocale('pl');
dd(\Carbon\Carbon::parse(' 12.11'));
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('a', function () {


})->purpose('Display an inspiring quote')->hourly();
