<?php

namespace Rosalana\Accounts\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthContract
{
    /**
     * Login user by email & password against Rosalana Basecamp,
     * create/update local user, store session/cookie if needed
     */
    public function login(string $email, string $password): Authenticatable;

    /**
     * Logout user (both local session/cookie and remote)
     */
    public function logout(): void;

    /**
     * Register user in Rosalana Basecamp + create local user record
     */
    public function register(string $name, string $email, string $password, string $password_confirmation): Authenticatable;

    /**
     * Refresh user token if it's expired
     */
    public function refresh(): void;

    /**
     * Get current user data
     */
    public function current(): Authenticatable;
}