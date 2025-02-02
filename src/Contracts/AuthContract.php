<?php

namespace Rosalana\Accounts\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthContract
{
    /**
     * Login user by email & password against Rosalana Basecamp,
     * create/update local user, store session/cookie if needed
     * 
     * @throws \Rosalana\Accounts\Exceptions\RosalanaAuthException
     * @throws \Rosalana\Accounts\Exceptions\RosalanaCredentialsException
     */
    public function login(array $credentials): Authenticatable;

    /**
     * Logout user (both local session/cookie and remote)
     * 
     * @throws \Rosalana\Accounts\Exceptions\RosalanaAuthException
     */
    public function logout(): void;

    /**
     * Register user in Rosalana Basecamp + create local user record
     * 
     * @throws \Rosalana\Accounts\Exceptions\RosalanaAuthException
     * @throws \Rosalana\Accounts\Exceptions\RosalanaCredentialsException
     */
    public function register(array $credentials): Authenticatable;

    /**
     * Refresh user token if it's expired
     * 
     * @throws \Rosalana\Accounts\Exceptions\RosalanaTokenRefreshException
     */
    public function refresh(): void;

    /**
     * Get current user data
     * 
     * @throws \Rosalana\Accounts\Exceptions\RosalanaAuthException
     */
    public function current(): Authenticatable;
}