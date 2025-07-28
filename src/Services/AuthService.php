<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Client\Response;
use Illuminate\Validation\ValidationException;
use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Exceptions\Http\BasecampUnauthorizedException;
use Rosalana\Core\Exceptions\Http\BasecampValidationException;
use Rosalana\Core\Facades\App;
use Rosalana\Core\Facades\Basecamp;

class AuthService
{
    public function login(array $credentials): User
    {
        try {
            $response = Basecamp::auth()->login($credentials);
        } catch (BasecampValidationException $e) {
            throw ValidationException::withMessages($e->getErrors());
        }

        return $this->authenticateAndSynchronize($response, 'login');
    }

    public function logout(): void
    {
        $user = Accounts::session()->current();
        Basecamp::auth()->logout();
        Accounts::session()->terminate();

        App::hooks()->run('user:logout', [
            'user' => collect($user?->toArray() ?? [])
                ->except('id')
                ->merge(['local_id' => $user?->id ?? null])
                ->all(),
        ]);
    }

    public function register(array $credentials): User
    {
        try {
            $response = Basecamp::auth()->register($credentials);
        } catch (BasecampValidationException $e) {
            throw ValidationException::withMessages($e->getErrors());
        }

        return $this->authenticateAndSynchronize($response, 'register');
    }

    public function refresh()
    {
        try {
            $response = Basecamp::auth()->refresh();
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
            return;
        }

        $token = $response->json('meta.token');
        $expiresAt = $response->json('meta.expires_at');

        Accounts::session()->refresh($token, $expiresAt);

        $user = Accounts::users()->toLocal($response->json('data'));

        App::hooks()->run('user:refresh', [
            'user' => [
                ...$response->json('data', []),
                'local_id' => $user?->id ?? null,
            ],
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }

    protected function authenticateAndSynchronize(Response $response, string $action): User
    {
        $basecampUser = $response->json('data', []);
        $token = $response->json('meta.token');
        $expiresAt = $response->json('meta.expires_at');

        $user = Accounts::users()->sync($basecampUser);
        Accounts::session()->authorize($user, $token, $expiresAt);

        App::hooks()->run('user:' . $action, [
            'user' => [
                ...$basecampUser,
                'local_id' => $user->id,
            ],
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        return $user;
    }
}
