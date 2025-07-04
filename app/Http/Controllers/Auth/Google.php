<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialiteUser;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class Google extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
//            ->setScopes(['openid', 'email'])
            ->stateless()
            ->redirect();
    }

    public function callback()
    {
        dd(1);
        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->user();

        } catch (ClientException $exception) {
            return redirect('/login')->withErrors([
                'login' => 'Authentication failed: ' . $exception->getMessage(),
            ]);
        } catch (\Throwable $exception) {
            return redirect('/login')->withErrors([
                'login' => 'Authentication failed: ' . $exception->getMessage(),
            ]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => encrypt(Str::random(16)),
            ]);
        }

        SocialiteUser::firstOrCreate([
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => $googleUser->getId(),
        ]);

        Auth::login($user);

        return redirect('/');
    }
}
