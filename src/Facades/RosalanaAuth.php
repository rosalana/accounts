<?php

namespace Rosalana\Accounts\Facades;

use Illuminate\Support\Facades\Facade;

class RosalanaAuth extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'rosalana.auth';
    }
}