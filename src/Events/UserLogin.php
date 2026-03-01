<?php

namespace Rosalana\Accounts\Events;

use Illuminate\Foundation\Auth\User;

class UserLogin
{
    public function __construct(public User $user, public string $token) {}
}
