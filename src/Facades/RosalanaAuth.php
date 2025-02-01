<?php

namespace Rosalana\Accounts\Facades;

use Illuminate\Support\Facades\Facade;
use Rosalana\Accounts\Contracts\AuthContract;

/**
 * @method static \Rosalana\Accounts\Contracts\AuthContract login(string $email, string $password)
 * @method static \Rosalana\Accounts\Contracts\AuthContract logout()
 * @method static \Rosalana\Accounts\Contracts\AuthContract register(string $name, string $email, string $password, string $password_confirmation)
 * @method static \Rosalana\Accounts\Contracts\AuthContract refresh()
 * @method static \Rosalana\Accounts\Contracts\AuthContract current()
 */

class RosalanaAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return AuthContract::class;
    }
}