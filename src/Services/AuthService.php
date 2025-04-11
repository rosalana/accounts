<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Rosalana\Accounts\Facades\Accounts;
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

        return $this->authenticateThroughBasecamp($response);
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

        return $this->authenticateThroughBasecamp($response);
    }

    public function refresh()
    {
        // add logic later...
        return Basecamp::users()->refresh();
    }

    public function current()
    {
        // add logic later...
        return Basecamp::users()->current();
    }

    public function find(string $id)
    {
        // add logic later...
        return Basecamp::users()->find($id);
    }

    protected function authenticateThroughBasecamp(Response $response): Authenticatable
    {
        $basecampUser = $response->json('data');
        $token = $response->json('meta.token');

        $user = Accounts::users()->sync($basecampUser);
        Accounts::session()->authorize($user, $token);

        return $user;
    }
}
