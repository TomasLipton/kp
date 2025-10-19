<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;
use URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! app()->environment('local')) {
            URL::forceScheme('https');
        }

        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('google', \SocialiteProviders\Google\Provider::class);
        });
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('telegram', \SocialiteProviders\Telegram\Provider::class);
        });
    }
}
