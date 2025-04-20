<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Core\Services\Basecamp\Service;

class AuthService extends Service
{
    public function login(array $credentials)
    {
        return $this->manager
            ->withPipeline('user.login')
            ->post('auth/login', $credentials);
    }

    public function logout()
    {
        return $this->manager
            ->withAuth()
            ->withPipeline('user.logout')
            ->post('auth/logout');
    }

    public function register(array $data)
    {
        return $this->manager
            ->withPipeline('user.register')
            ->post('auth/register', $data);
    }

    public function refresh()
    {
        return $this->manager
            ->withAuth()
            ->withPipeline('user.refresh')
            ->post('auth/refresh');
    }

    public function current()
    {
        return $this->manager
            ->withAuth()
            ->withPipeline('user.current')
            ->get('auth/me');
    }
}