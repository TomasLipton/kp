<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialiteUser;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class Telegram extends Controller
{

    public function callback()
    {
        try {
            $telegramUser = Socialite::driver('telegram')
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

        // For Telegram, we might not have email, so we'll use the Telegram ID as unique identifier
        $user = null;
        if ($telegramUser->getEmail()) {
            $user = User::where('email', $telegramUser->getEmail())->first();
        }

        // If no user found by email, check by Telegram provider ID
        if (! $user) {
            $socialiteUser = SocialiteUser::where('provider', 'telegram')
                ->where('provider_id', $telegramUser->getId())
                ->first();

            if ($socialiteUser) {
                $user = $socialiteUser->user;
            }
        }

        if (! $user) {
            // Create user - use Telegram username or first name for name
            $name = $telegramUser->getName() ?: $telegramUser->getNickname() ?: 'Telegram User';
            $email = $telegramUser->getEmail() ?: $telegramUser->getId().'@telegram.placeholder';

            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => encrypt(Str::random(16)),
            ]);
        }

        $socialiteUser = SocialiteUser::where('provider', 'telegram')
            ->where('provider_id', $telegramUser->getId())
            ->first();

        if ($socialiteUser && $socialiteUser->user_id !== $user->id) {
            // Ошибка — провайдер уже связан с другим пользователем
            return redirect('/login')->withErrors([
                'login' => 'To konto Telegram jest już powiązane z innym użytkownikiem.',
            ]);
        }

        if (! $socialiteUser) {
            SocialiteUser::create([
                'user_id' => $user->id,
                'provider' => 'telegram',
                'provider_id' => $telegramUser->getId(),
            ]);
        }

        Auth::login($user, true);

        return redirect('/');
    }
}
