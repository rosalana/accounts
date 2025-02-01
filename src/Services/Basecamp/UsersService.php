<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Accounts\Exceptions\RosalanaAuthException;
use Rosalana\Accounts\Exceptions\RosalanaCredentialsException;
use Rosalana\Accounts\Exceptions\RosalanaTokenRefreshException;
use Rosalana\Accounts\Services\RosalanaSession;
use Rosalana\Core\Services\Basecamp\Service;

class UsersService extends Service
{
    public function login(string $email, string $password)
    {
        $response = $this->manager->post('/api/v1/login', compact('email', 'password'));

        if ($response->json('error')) {
            throw new RosalanaAuthException($response->json('error'), 401);
        }

        if ($response->json('errors')) {
            throw new RosalanaCredentialsException($response->json('errors'), 401);
        }

        return $response;
    }

    public function logout()
    {
        $token = RosalanaSession::get();
        $response = $this->manager->withAuth($token)->post('/api/v1/logout');

        if ($response->json('error')) {
            throw new RosalanaAuthException($response->json('error'), 401);
        }

        return $response;
    }

    public function register(string $name, string $email, string $password, string $password_confirmation)
    {
        $response = $this->manager->post('/api/v1/register', compact('name', 'email', 'password', 'password_confirmation'));

        if ($response->json('error')) {
            throw new RosalanaAuthException($response->json('error'), 401);
        }

        if ($response->json('errors')) {
            throw new RosalanaCredentialsException($response->json('errors'), 401);
        }

        return $response;
    }

    public function refresh()
    {
        $token = RosalanaSession::get();
        $response = $this->manager->withAuth($token)->post('/api/v1/refresh');

        if ($response->json('error')) {
            throw new RosalanaTokenRefreshException($response->json('error'), 401);
        }

        return $response;
    }

    public function current()
    {
        $token = RosalanaSession::get();
        $response = $this->manager->withAuth($token)->get('/api/v1/me');

        if ($response->json('error')) {
            throw new RosalanaAuthException($response->json('error'), 401);
        }

        return $response;
    }
}
