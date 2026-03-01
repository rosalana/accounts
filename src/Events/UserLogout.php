<?php

namespace Rosalana\Accounts\Events;

use Illuminate\Foundation\Auth\User;

class UserLogout
{
    public function __construct(public User $user) {}
}
