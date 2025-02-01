<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Support\Facades\Cookie;

class RosalanaSession
{
    public static function create(string $token)
    {
        Cookie::queue(Cookie::make('RA-TOKEN', $token, 0, null, null, false, false, true));
    }

    public static function forget()
    {
        Cookie::queue(Cookie::forget('RA-TOKEN'));
    }

    public static function get()
    {
        return Cookie::get('RA-TOKEN');
    }
}