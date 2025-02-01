<?php

namespace Rosalana\Accounts\Services;

use Rosalana\Accounts\Contracts\AuthContract;
use Rosalana\Accounts\Exceptions\RosalanaAuthException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthService implements AuthContract
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function login(string $email, string $password): array
    {
        // $response = $this->client->login($email, $password);

        // if ($response->status() !== 200) {
        //     throw new RosalanaAuthException('Login failed', $response->status());
        // }

        throw new RosalanaAuthException('Login failed', 401);

        return [];
    }

    public function logout(): void
    {
        // $this->client->logout();
    }

    public function register(string $name, string $email, string $password, string $password_confirmation): array
    {
        // $response = $this->client->register($name, $email, $password, $password_confirmation);

        // if ($response->status() !== 200) {
        //     throw new RosalanaAuthException('Registration failed', $response->status());
        // }

        return [];
    }

    public function refresh(string $token): array
    {
        // $response = $this->client->refresh($token);

        // if ($response->status() !== 200) {
        //     throw new RosalanaAuthException('Token refresh failed', $response->status());
        // }

        return [];
    }
}