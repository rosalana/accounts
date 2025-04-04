<?php

namespace Rosalana\Accounts\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Rosalana\Accounts\Services\AccountsManager;
use Rosalana\Core\Services\Basecamp\Manager;

class RosalanaAccountsServiceProvider extends ServiceProvider
{
    /**
     * Register everything in the container.
     */
    public function register()
    {
        $this->app->singleton('rosalana.accounts', function() {
            return new AccountsManager(
                new \Rosalana\Accounts\Services\AuthService(),
                new \Rosalana\Accounts\Session\TokenSession(),
                new \Rosalana\Accounts\Session\AuthSession(),
                new \Rosalana\Accounts\Services\UsersService()
            );
        });

        $this->app->resolving('rosalana.basecamp', function (Manager $manager) {
            $manager->registerService('users', new \Rosalana\Accounts\Services\Basecamp\UsersService());
        });
    }

    /**
     * Boot services.
     */
    public function boot(Router $router)
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'rosalana-accounts-migrations');
    }
}
