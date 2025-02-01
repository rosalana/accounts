<?php

namespace Rosalana\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Rosalana\Accounts\Contracts\AuthContract;
use Rosalana\Accounts\Services\AuthService;
use Rosalana\Core\Services\Basecamp\Manager;

class RosalanaAccountsServiceProvider extends ServiceProvider
{
    /**
     * Register everything in the container.
     */
    public function register()
    {
        $this->app->singleton(AuthContract::class, function($app) {
            return new AuthService($app->make(\Rosalana\Accounts\Services\Client::class));
        });

        $this->app->resolving('rosalana.basecamp', function (Manager $manager) {
            $manager->registerService('users', new \Rosalana\Accounts\Services\Basecamp\UsersService());
        });

        // Co když chci dopsat do rosalana.php něco navíc? a nevytvářet nový?
        $this->mergeConfigFrom(__DIR__ . '/../../config/rosalana-accounts.php', 'rosalana');
    }

    /**
     * Boot services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'rosalana-accounts-migrations');
    }
}
