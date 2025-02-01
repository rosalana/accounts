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
        $response = $this->client->login($email, $password);

        if ($response->status() !== 200) {
            throw new RosalanaAuthException('Login failed', $response->status());
        }

    }
}