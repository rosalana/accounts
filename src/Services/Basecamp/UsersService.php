<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Services\Basecamp\Service;

class UsersService extends Service
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
            ->withAuth(Accounts::token()->get())
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
            ->withAuth(Accounts::token()->get())
            ->withPipeline('user.refresh')
            ->post('auth/refresh');
    }

    public function current()
    {
        return $this->manager
            ->withAuth(Accounts::token()->get())
            ->withPipeline('user.current')
            ->get('auth/me');
    }

    public function find(string $id)
    {
        return $this->manager
            ->withAuth(Accounts::token()->get())
            ->get("/users/{$id}");
    }
}
