<?php

namespace Rosalana\Accounts\Events;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Client\Response;

class UserLogout
{
    public function __construct(public User $user, public Response $response) {}
}
