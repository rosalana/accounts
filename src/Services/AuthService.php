<?php

namespace Rosalana\Accounts\Services;

use Rosalana\Core\Facades\Basecamp;

class AuthService
{
    public function login(array $credentials)
    {
        // add logic later...
        return Basecamp::users()->login($credentials);
    }

    public function logout()
    {
        // add logic later...
        return Basecamp::users()->logout();
    }

    public function register(array $credentials)
    {
        // add logic later...
        return Basecamp::users()->register($credentials);
    }

    public function refresh()
    {
        // add logic later...
        return Basecamp::users()->refresh();
    }

    public function current()
    {
        // add logic later...
        return Basecamp::users()->current();
    }

    public function find(string $id)
    {
        // add logic later...
        return Basecamp::users()->find($id);
    }
}