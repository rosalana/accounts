<?php

namespace Rosalana\Accounts\Session;

use Illuminate\Support\Facades\Auth;

class AuthSession
{
    // soubor pro plný přístup k session

    public static function forget(): void
    {
        Auth::logout();
        TokenSession::forget();
    }
}