<?php

namespace Rosalana\Accounts\Services;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Client\Response;
use Illuminate\Validation\ValidationException;
use Rosalana\Accounts\Events\UserLogin;
use Rosalana\Accounts\Events\UserLogout;
use Rosalana\Accounts\Events\UserRefresh;
use Rosalana\Accounts\Events\UserRegister;
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

        return $this->authenticateAndSynchronize($response, UserLogin::class);
    }

    public function logout(): void
    {
        $user = Accounts::session()->current();
        Basecamp::auth()->logout();
        Accounts::session()->terminate();

        event(new UserLogout($user));
    }

    public function register(array $credentials): User
    {
        try {
            $response = Basecamp::auth()->register($credentials);
        } catch (BasecampValidationException $e) {
            throw ValidationException::withMessages($e->getErrors());
        }

        return $this->authenticateAndSynchronize($response, UserRegister::class);
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

        $user = Accounts::users()->toLocal($response->json('data.id'));

        $ctx = App::context()->scope("user.{$user->id}");
        $ctx->put('local_id', $user->id);
        $ctx->put('remote_id', $response->json('data.id'));

        Accounts::session()->refresh($token, $expiresAt);

        event(new UserRefresh($user, $token));
    }

    protected function authenticateAndSynchronize(Response $response, string $action): User
    {
        $basecampUser = $response->json('data', []);
        $token = $response->json('meta.token');
        $expiresAt = $response->json('meta.expires_at');

        $user = Accounts::users()->sync($basecampUser);

        $ctx = App::context()->scope("user.{$user->id}");
        $ctx->put('local_id', $user->id);
        $ctx->put('remote_id', $basecampUser['id']);

        Accounts::session()->authorize($user, $token, $expiresAt);

        event(new $action($user, $token));

        return $user;
    }
}
