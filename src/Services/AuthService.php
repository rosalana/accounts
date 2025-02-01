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

        $data = $response->json()['data'] ?? [];
        $token = $data['token'] ?? null;
        $userData = $data['user'] ?? [];

        $localUser = \App\Models\User::updateOrCreate(
            ['rosalana_account_id' => $user['id']],
            [
                'name' => $user['name'] ?? $user['email'],
                'email' => $user['email'],
            ]
        );

        Auth::login($localUser);
        RosalanaSession::create($token);

        return [
            'user' => $localUser->toArray(),
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        Auth::logout();
        RosalanaSession::forget();

        $token = RosalanaSession::get();
        if ($token) {
            $response = $this->client->logout($token);

            // optional error handling
        }
    }

    public function register(string $name, string $email, string $password, string $password_confirmation): array
    {
        // todo
        return [];
    }

    public function refresh(string $token): array
    {
        // todo
        return [];
    }
}