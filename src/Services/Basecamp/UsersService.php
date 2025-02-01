<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Rosalana\Core\Services\Basecamp\Service;

class UsersService extends Service
{
    public function login(string $email, string $password): Response
    {
        return $this->manager->post('/api/v1/login', [
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function logout(): Response
    {
        return $this->manager->post('/api/v1/logout');
    }

    public function register(string $name, string $email, string $password, string $password_confirmation): Response
    {
        return $this->manager->post('/api/v1/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ]);
    }

    public function refresh(): Response
    {
        return $this->manager->post('/api/v1/refresh');
    }

    public function current(): Response
    {
        return $this->manager->get('/api/v1/me');
    }
}
