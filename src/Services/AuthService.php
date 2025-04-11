<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Exceptions\BasecampUnauthorizedException;
use Rosalana\Core\Exceptions\BasecampValidationException;
use Rosalana\Core\Facades\Basecamp;

class AuthService
{
    public function login(array $credentials): Authenticatable
    {
        try {
            $response = Basecamp::users()->login($credentials);
        } catch (BasecampValidationException $e) {
            throw ValidationException::withMessages($e->getErrors());
        }

        return $this->authenticateAndSynchronize($response);
    }

    public function logout(): void
    {
        Basecamp::users()->logout();
        Accounts::session()->terminate();
    }

    public function register(array $credentials): Authenticatable
    {
        try {
            $response = Basecamp::users()->register($credentials);
        } catch (BasecampValidationException $e) {
            throw ValidationException::withMessages($e->getErrors());
        }

        return $this->authenticateAndSynchronize($response);
    }

    public function refresh()
    {
        try {
            $response = Basecamp::users()->refresh();
        } catch (BasecampUnauthorizedException $e) {
            Accounts::session()->terminate();
        }

        $token = $response->json('meta.token');

        Accounts::session()->refresh($token);
    }

    protected function authenticateAndSynchronize(Response $response): Authenticatable
    {
        $basecampUser = $response->json('data');
        $token = $response->json('meta.token');

        $user = Accounts::users()->sync($basecampUser);
        Accounts::session()->authorize($user, $token);

        return $user;
    }
}
