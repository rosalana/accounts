<?php

namespace Rosalana\Accounts\Session;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Rosalana\Accounts\Facades\Accounts;

class AuthSession
{
    public static function terminate(): void
    {
        Auth::logout();
        // TokenSession::forget();
        Accounts::token()->forget();

        session()->invalidate();
        session()->regenerateToken();
    }

    public static function authorize(Authenticatable $user, string $token): void
    {
        Auth::login($user);
        // TokenSession::set($token);
        Accounts::token()->set($token);

        session()->regenerate();
    }
}