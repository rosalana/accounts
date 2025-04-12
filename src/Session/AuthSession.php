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
        Accounts::token()->forget();

        session()->invalidate();
        session()->regenerateToken();
    }

    public static function authorize(Authenticatable $user, string $token, ?string $expiresAt = null): void
    {
        Auth::login($user);
        Accounts::token()->set($token, $expiresAt);

        session()->regenerate();
    }

    public static function current(): ?Authenticatable
    {
        return Auth::user();
    }

    public static function refresh(string $token, ?string $expiresAt = null): void
    {
        static::authorize(
            static::current(),
            $token,
            $expiresAt
        );
    } 
}