<?php

namespace Rosalana\Accounts\Session;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class AuthSession
{
    public static function terminate(): void
    {
        Auth::logout();
        TokenSession::forget();

        session()->invalidate();
        session()->regenerateToken();
    }

    public static function authorize(Authenticatable $user, string $token): void
    {
        Auth::login($user);
        TokenSession::set($token);

        session()->regenerate();
    }
}