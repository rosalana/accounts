<?php

namespace Rosalana\Accounts\Events;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Client\Response;

class UserRefresh
{
    public function __construct(public User $user, public Response $response, public string $token) {}
}
