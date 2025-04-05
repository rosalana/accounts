<?php

namespace Rosalana\Accounts\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Rosalana\Accounts\Contracts\AuthContract;
use Rosalana\Accounts\Services\AccountsManager;
use Rosalana\Accounts\Services\AuthService;
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
                new \Rosalana\Accounts\Session\TokenSession()
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
