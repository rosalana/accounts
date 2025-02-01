<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Rosalana\Accounts\Contracts\AuthContract;
use Rosalana\Accounts\Exceptions\RosalanaAuthException;
use Illuminate\Support\Facades\Auth;
use Rosalana\Core\Facades\Basecamp;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService implements AuthContract
{
    public function login(string $email, string $password): Authenticatable
    {
        $response = Basecamp::users()->login($email, $password)->json();

        if (isset($response['error'])) {
            throw new RosalanaAuthException($response['error']);
        }

        $user = $response['data']['user'];
        $token = $response['data']['token'];

        $localUser = \App\Models\User::updateOrCreate(
            ['rosalana_account_id' => $user['id']],
            [
                'name' => $user['name'] ?? $user['email'],
                'email' => $user['email'],
            ]
        );

        Auth::login($localUser);
        RosalanaSession::create($token);

        // fire event

        return $localUser;
    }

    public function logout(): void
    {
        $response = Basecamp::users()->logout();

        if (isset($response['error'])) {
            throw new RosalanaAuthException($response['error']);
        }

        Auth::logout();
        RosalanaSession::forget();
    }

    public function register(string $name, string $email, string $password, string $password_confirmation): Authenticatable
    {
        $response = Basecamp::users()->register($name, $email, $password, $password_confirmation)->json();

        if (isset($response['error'])) {
            throw new RosalanaAuthException($response['error']);
        }

        $user = $response['data']['user'];
        $token = $response['data']['token'];

        $localUser = \App\Models\User::updateOrCreate(
            ['rosalana_account_id' => $user['id']],
            [
                'name' => $user['name'] ?? $user['email'],
                'email' => $user['email'],
            ]
        );

        Auth::login($localUser);
        RosalanaSession::create($token);

        // fire event

        return $localUser;
    }

    public function refresh(): void
    {
        $response = Basecamp::users()->refresh()->json();

        if (isset($response['error'])) {
            Auth::logout();
            RosalanaSession::forget();

            session()->invalidate();
            session()->regenerateToken();
        } else {
            try {
                $token = $response['data']['token'];
                RosalanaSession::create($token);
                $decode = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                $user = App\Models\User::where('rosalana_account_id', $decode->sub)->first();
                Auth::login($user);
                session()->regenerate();
            } catch (\Exception $e) {
                throw new RosalanaAuthException('Token is invalid');
            }
        }
    }

    public function current(): Authenticatable
    {
        $response = Basecamp::users()->current();

        if (isset($response['error'])) {
            throw new RosalanaAuthException($response['error']);
        }

        $user = $response['data']['user'];

        return \App\Models\User::where('rosalana_account_id', $user['id'])->firstOrFail();
    }
}
