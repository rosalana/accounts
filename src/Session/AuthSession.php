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
        Accounts::token()->set($token);

        session()->regenerate();
    }

    public static function current(): ?Authenticatable
    {
        return Auth::user();
    }

    public static function refresh($token): void
    {
        static::authorize(
            static::current(),
            $token
        );
    } 
}