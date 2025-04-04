<?php

namespace Rosalana\Accounts\Services\Basecamp;

use Rosalana\Core\Services\Basecamp\Service;

class UsersService extends Service
{

    public function login(array $credentials)
    {
        $response = $this->manager->post('/api/v1/login', $credentials);

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

    public function register(array $credentials)
    {
        $response = $this->manager->post('/api/v1/register', $credentials);

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
