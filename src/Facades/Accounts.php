<?php

namespace Rosalana\Accounts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Rosalana\Accounts\Session\TokenSession token()
 * @method static \Rosalana\Accounts\Session\AuthSession session()
 * @method static \Rosalana\Accounts\Services\UsersService users()
 * @method static mixed login(array $credentials)
 * @method static mixed logout()
 * @method static mixed register(array $credentials)
 * @method static mixed refresh()
 * @method static mixed current()
 * @method static mixed find(string $id)
 */
class Accounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rosalana.accounts';
    }
}
