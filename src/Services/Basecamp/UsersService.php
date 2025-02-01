<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Accounts\Services\RosalanaSession;
use Rosalana\Core\Services\Basecamp\Service;

class UsersService extends Service
{
    public function login(string $email, string $password)
    {
        return $this->manager->post('/api/v1/login', [
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function logout()
    {
        $token = RosalanaSession::get();
        return $this->manager->withAuth($token)->post('/api/v1/logout');
    }

    public function register(string $name, string $email, string $password, string $password_confirmation)
    {
        return $this->manager->post('/api/v1/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation,
        ]);
    }

    public function refresh()
    {
        $token = RosalanaSession::get();
        return $this->manager->withAuth($token)->post('/api/v1/refresh');
    }

    public function current()
    {
        $token = RosalanaSession::get();
        return $this->manager->withAuth($token)->get('/api/v1/me');
    }
}
