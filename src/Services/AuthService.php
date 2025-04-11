<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Validation\ValidationException;
use Rosalana\Accounts\Facades\Accounts;
use Rosalana\Core\Facades\Basecamp;

class AuthService
{
    public function login(array $credentials)
    {
        try {
            $response = Basecamp::users()->login($credentials);
        } catch (\Rosalana\Core\Exceptions\BasecampValidationException $e) {
            throw ValidationException::withMessages($e->getErrors());
        }

        $basecampUser = $response->json('data');
        $token = $response->json('meta.token');

        $user = Accounts::users()->sync($basecampUser);
        Accounts::session()->authorize($user, $token);

        return $user;
    }

    public function logout()
    {
        // add logic later...
        return Basecamp::users()->logout();
    }

    public function register(array $credentials)
    {
        // add logic later...
        return Basecamp::users()->register($credentials);
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
}