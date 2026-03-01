<?php

namespace Rosalana\Accounts\Events;

use Illuminate\Foundation\Auth\User;

class UserRefresh
{
    public function __construct(public User $user, public string $token) {}
}
