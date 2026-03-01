<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Core\Services\Basecamp\Service;

class AuthService extends Service
{
    public function login(array $credentials)
    {
        return $this->manager
            ->withAlias('user.login')
            ->post('auth/login', $credentials);
    }

    public function logout()
    {
        return $this->manager
            ->withAuth()
            ->withAlias('user.logout')
            ->post('auth/logout');
    }

    public function register(array $data)
    {
        return $this->manager
            ->withAlias('user.register')
            ->post('auth/register', $data);
    }

    public function refresh()
    {
        return $this->manager
            ->withAuth()
            ->withAlias('user.refresh')
            ->post('auth/refresh');
    }

    public function current()
    {
        return $this->manager
            ->withAuth()
            ->withAlias('user.current')
            ->get('auth/me');
    }
}