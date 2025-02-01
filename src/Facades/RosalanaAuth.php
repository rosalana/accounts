<?php

namespace Rosalana\Accounts\Facades;

use Illuminate\Support\Facades\Facade;
use Rosalana\Accounts\Contracts\AuthContract;

class RosalanaAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AuthContract::class;
    }
}