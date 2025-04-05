<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Rosalana\Accounts\Contracts\AuthContract;
use Illuminate\Support\Facades\Auth;
use Rosalana\Core\Facades\Basecamp;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService implements AuthContract
{
    public function login(array $credentials): Authenticatable
    {
        $response = Basecamp::users()->login($credentials);

        $user = $response->json('data.user');
        $token = $response->json('data.token');

        $localUser = $this->syncUser($user);

        $this->localLogin($localUser, $token);

        // fire event

        return $localUser;
    }

    public function logout(): void
    {
        Basecamp::users()->logout();
        $this->localLogout();
    }

    public function register(array $credentials): Authenticatable
    {
        $response = Basecamp::users()->register($credentials);

        $user = $response->json('data.user');
        $token = $response->json('data.token');

        $localUser = $this->syncUser($user);

        $this->localLogin($localUser, $token);

        // fire event

        return $localUser;
    }

    public function refresh(): void
    {
        try {
            $response = Basecamp::users()->refresh();
        } catch (\Rosalana\Accounts\Exceptions\RosalanaTokenRefreshException $e) {
            $this->localLogout();
            throw $e;
        }

        $token = $response->json('data.token');

        $decode = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
        $user = \App\Models\User::where('rosalana_account_id', $decode->sub)->first();

        $this->localLogin($user, $token);

        // fire event
    }

    public function current(): Authenticatable
    {
        $response = Basecamp::users()->current();

        $user = $response->json('data.user');

        return \App\Models\User::where('rosalana_account_id', $user['id'])->firstOrFail();
    }


    public function syncUser($user)
    {
        return \App\Models\User::updateOrCreate(
            ['rosalana_account_id' => $user['id']],
            [
                'name' => $user['name'] ?? $user['email'],
                'email' => $user['email'],
            ]
        );
    }

    public function localLogin($localUser, $token)
    {
        Auth::login($localUser);
        RosalanaSession::create($token);

        session()->regenerate();
    }

    public function localLogout()
    {
        Auth::logout();
        RosalanaSession::forget();

        session()->invalidate();
        session()->regenerateToken();
    }
}
