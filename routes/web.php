<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function () {

    Route::get('/subscribe', function (Request $request) {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Determine which price ID to use based on plan parameter
        $plan = $request->query('plan', 'monthly');
        $priceId = $plan === 'weekly'
            ? 'price_weekly_id_here' // Replace with your Stripe weekly price ID
            : 'price_1SAtFwPkPU7QethbODxZxYbb'; // Monthly price ID

        return $user->newSubscription('default', $priceId)
            ->checkout([
                'success_url' => route('profile').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('pricing'),
            ]);

    })->name('subscribe');
    //
    //    Route::get('/billing-portal', function (Request $request) {
    //        $user = \Illuminate\Support\Facades\Auth::user();
    //
    //        return $user->redirectToBillingPortal();
    //    });

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });

    Route::get('/', \App\Livewire\Main::class)->name('dashboard');

    // Alias for Cashier billing portal return URL
    Route::redirect('/home', '/')->name('home');

    Volt::route('topics', 'topics')->name('topics');

    Volt::route('unlock', 'pricing')->name('pricing');

    Route::get('profile', \App\Livewire\Profile::class)
        ->middleware(['auth'])
        ->name('profile');

    Volt::route('analytics', 'analytics')
        ->middleware(['auth'])
        ->name('analytics');

    //    Route::get('ai-realtime-play/{quiz}', \App\Livewire\AiPage::class)
    //        ->middleware(['auth'])
    //        ->name('ai');
    //
    //    Volt::route('ai-realtime-configure', 'ai-realtime-configure')->name('ai-quiz');
    Volt::route('history', 'ai-quiz-history')
        ->middleware(['auth'])
        ->name('ai-quiz-history');
    Volt::route('ai-voice-quiz', 'ai-sync-configure')->name('ai-sync-configure');
    Volt::route('ai-voice-quiz/{quiz}', 'ai-sync-play')->name('voice-quiz');
    Volt::route('ai-voice-quiz/{quiz}/summary', 'ai-quiz-summary')->name('ai-quiz-summary');

    require __DIR__.'/auth.php';

    Route::get('/{topic:slug}', \App\Livewire\StartSurvey::class);
    Route::get('/{topic:slug}/{quiz:uuid}', \App\Livewire\SurveyQuestion::class);

});
