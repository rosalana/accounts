<?php

namespace Rosalana\Accounts\Contracts;

use Rosalana\Accounts\Exeptions\RosalanaAuthException;

interface AuthContract
{
    /**
     * Login user by email & password against Rosalana Basecamp,
     * create/update local user, store session/cookie if needed
     */
    public function login(string $email, string $password): array;

    /**
     * Logout user (both local session/cookie and remote)
     */
    public function logout(): void;

    /**
     * Register user in Rosalana Basecamp + create local user record
     */
    public function register(string $name, string $email, string $password, string $password_confirmation): array;

    /**
     * Refresh user token if it's expired
     */
    public function refresh(string $token): array;

    /**
     * #idea
     * Rozdělit funkci `login` na dvě části:
     * 1. `attempt` - zkusí přihlásit uživatele pokud vyjde tak je přihlášený
     * 2. `login` - přihlášení uživatele lokálně (session/cookie)
     * 
     * Tedy přihlášování (v controlleru už) by mělo vypadat že se prvně zavolá attempt a pokud vyjde tak se zavolá login
     */
}