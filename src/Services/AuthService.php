<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Rosalana\Accounts\Contracts\AuthContract;
use Rosalana\Accounts\Exceptions\RosalanaAuthException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Rosalana\Core\Facades\Basecamp;

class AuthService implements AuthContract
{
    public function login(string $email, string $password): Authenticatable
    {
        $response = Basecamp::users()->login($email, $password);
    }

    public function logout(): void
    {
        $response = Basecamp::users()->logout();
    }

    public function register(string $name, string $email, string $password, string $password_confirmation): Authenticatable
    {
        $response = Basecamp::users()->register($name, $email, $password, $password_confirmation);
    }

    public function refresh(string $token): array
    {
        $response = Basecamp::users()->refresh();
    }

    public function current(string $token): Authenticatable
    {
        $response = Basecamp::users()->current();
    }

}