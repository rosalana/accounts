<?php

namespace Rosalana\Accounts\Events;

use Illuminate\Foundation\Auth\User;

class UserRegister
{
    public function __construct(public User $user, public string $token) {}
}
