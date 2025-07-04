<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialiteUser;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class Google extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->user();

        } catch (ClientException $exception) {
            return redirect('/login')->withErrors([
                'login' => 'Uwierzytelnienie nie powiodło się: '.$exception->getMessage(),
            ]);
        } catch (\Throwable $exception) {
            return redirect('/login')->withErrors([
                'login' => 'Uwierzytelnienie nie powiodło się: '.$exception->getMessage(),
            ]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => encrypt(Str::random(16)),
            ]);
        }

        $socialiteUser = SocialiteUser::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if ($socialiteUser && $socialiteUser->user_id !== $user->id) {
            // Ошибка — провайдер уже связан с другим пользователем
            return redirect('/login')->withErrors([
                'login' => 'To konto Google jest już powiązane z innym użytkownikiem.',
            ]);
        }

        if (! $socialiteUser) {
            SocialiteUser::create([
                'user_id' => $user->id,
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
            ]);
        }

        Auth::login($user);

        return redirect('/');
    }
}
