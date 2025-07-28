<?php

namespace Rosalana\Accounts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Rosalana\Core\Session\TokenSession token()
 * @method static \Rosalana\Accounts\Session\AuthSession session()
 * @method static \Rosalana\Accounts\Services\UsersService users()
 * @method static mixed login(array $credentials)
 * @method static mixed logout()
 * @method static mixed register(array $credentials)
 * @method static mixed refresh()
 * 
 * @see \Rosalana\Accounts\Services\AccountsManager
 */
class Accounts extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rosalana.accounts';
    }
}
