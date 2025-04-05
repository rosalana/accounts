<?php

namespace Rosalana\Accounts\Session;

class TokenSession
{
    public static function set(string $token): void
    {
        session()->put('rosalana.token', $token);
    }

    public static function get(): ?string
    {
        return session()->get('rosalana.token');
    }

    public static function forget(): void
    {
        session()->forget('rosalana.token');
    }
}