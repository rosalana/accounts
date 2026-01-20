<?php

namespace Rosalana\Accounts\Session;

use Illuminate\Foundation\Auth\User;
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

    public static function authorize(User $user, string $token, ?string $expiresAt = null): void
    {
        Auth::login($user);
        Accounts::token()->set($token, $expiresAt);

        session()->regenerate();
    }

    public static function current(): ?User
    {
        return Auth::user();
    }

    public static function refresh(string $token, ?string $expiresAt = null): void
    {
        Auth::setUser(Auth::user());
        Accounts::token()->set($token, $expiresAt);
    }

    public function terminateById(int $id): void
    {
        return;
    }

    public function active(?User $user): array
    {
        return [];
    }
}
