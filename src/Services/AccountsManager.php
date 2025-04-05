<?php

namespace Rosalana\Accounts\Services;

use Rosalana\Accounts\Session\AuthSession;
use Rosalana\Accounts\Session\TokenSession;

class AccountsManager
{
    public function __construct(
        protected AuthService $auth,
        protected TokenSession $token,
        protected AuthSession $session
    ) {}

    public function token(): TokenSession
    {
        return $this->token;
    }

    public function session(): AuthSession
    {
        return $this->session;
    }

    public function __call(string $method, array $arguments)
    {
        if (method_exists($this->auth, $method)) {
            return $this->auth->{$method}(...$arguments);
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}
